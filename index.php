<?php

$games = glob("*.zip");


$selectedGame = isset($_GET['game']) ? basename($_GET['game']) : null;


$gameExe = $selectedGame ? str_ireplace('.zip', '.exe', $selectedGame) : null;
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOS Game Library</title>
    
    <script src="https://js-dos.com/6.22/current/js-dos.js"></script>
    
    <style>
        /* --- LAYOUT STYLES --- */
        body {
            margin: 0;
            font-family: 'Courier New', Courier, monospace;
            background-color: #2c3e50;
            color: #ecf0f1;
            display: flex; /* Creates the Sidebar + Main layout */
            height: 100vh;
            overflow: hidden;
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            background-color: #1a252f;
            border-right: 2px solid #34495e;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .sidebar-header {
            padding: 20px;
            background-color: #000;
            text-align: center;
            font-weight: bold;
            font-size: 1.2em;
            border-bottom: 1px solid #34495e;
            color: #e74c3c; /* Retro red */
        }

        .game-link {
            display: block;
            padding: 15px 20px;
            color: #bdc3c7;
            text-decoration: none;
            border-bottom: 1px solid #2c3e50;
            transition: all 0.2s;
        }

        .game-link:hover {
            background-color: #34495e;
            color: #fff;
            padding-left: 25px; /* Slide effect */
        }

        .game-link.active {
            background-color: #27ae60;
            color: white;
            font-weight: bold;
        }

        /* MAIN CONTENT AREA */
        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #2c3e50;
            position: relative;
        }

        h1 { margin-bottom: 20px; text-shadow: 2px 2px #000; }
        
        /* THE GAME PLAYER */
        #game-container {
            width: 900px; /* Adjusted to fit nicely next to sidebar */
            height: 600px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            background: #000;
            border: 2px solid #7f8c8d;
        }
        
        canvas { width: 100%; height: 100%; }
        
        .instructions { margin-top: 15px; font-size: 0.9em; opacity: 0.8; text-align: center; }
        .placeholder { color: #7f8c8d; font-size: 1.5em; text-align: center; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">DISK OPERATING SYSTEM</div>
        
        <?php foreach ($games as $game): ?>
            <?php 
                // Format the name nicely
                $displayName = strtoupper(str_replace('.zip', '', $game));
                $isActive = ($selectedGame === $game) ? 'active' : '';
            ?>
            <a href="?game=<?php echo urlencode($game); ?>" class="game-link <?php echo $isActive; ?>">
                > <?php echo $displayName; ?>
            </a>
        <?php endforeach; ?>

        <?php if (empty($games)): ?>
            <div style="padding:20px; color: yellow;">No .zip files found in this folder.</div>
        <?php endif; ?>
    </div>

    <div class="main-content">
        
        <?php if ($selectedGame && file_exists($selectedGame)): ?>
            
            <h1><?php echo strtoupper(str_replace('.zip', '', $selectedGame)); ?></h1>

            <div id="game-container">
                <canvas id="jsdos"></canvas>
            </div>

           

            <script>
                Dos(document.getElementById("jsdos"), {
                    wdosboxUrl: "https://js-dos.com/6.22/current/wdosbox.js",
                    cycles: 1000, 
                    autolock: false,
                }).ready(function (fs, main) {
                    // Extract the DYNAMICALLY selected zip
                    fs.extract("<?php echo $selectedGame; ?>").then(function () {
                        // Run the DYNAMICALLY named exe
                        main(["-c", "<?php echo $gameExe; ?>"]); 
                    });
                });
            </script>

        <?php else: ?>
            
            <div class="placeholder">
                <p>READY.</p>
              
            </div>

        <?php endif; ?>
        
    </div>

</body>
</html>