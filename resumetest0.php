<!DOCTYPE html>
<html>
    <head>
        <title>CAREER</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        <style>
            * {
                margin: 0;
                padding: 0;
            }
            html { height: 100%; }
            body {
                position: fixed;
                width: 100%;
                height: 100%;
                background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAIAAAACDbGyAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAvSURBVBhXY7x165aoqCgDDDABOa9fv4bygHwgRhYC8YEALsT4/v17iBAQvH79GgBylhFk6ewVSAAAAABJRU5ErkJggg==);
                z-index: 0;
            }
            div#back {
                position: absolute;
                width: 100%;
                height: 100%;
                background: -webkit-radial-gradient(ellipse, #FFF, #000); /* Safari 5.1-6.0 */
                background: -o-radial-gradient(ellipse, #FFF, #000); /* Opera 11.6-12.0 */
                background: -moz-radial-gradient(ellipse, #FFF, #000); /* Firefox 3.6-15 */
                background: linear-gradient(to left, #000, #AAA, #AAA, #000);
                opacity: 0.6;
                z-index: 1;
            }
            div#data {
                margin: 0 auto;
                position: relative;
                max-width: 980px;
                height: 100%;
                box-shadow: 0 0 10px 0 #333;
                z-index: 2;
            }
            iframe { background-color: #FFF; }
        </style>
    </head>
    <body>
        <div id="back"></div>
        <div id="data">
            <!-- ФРЕЙМА ВЫВОДА / НАЧАЛО -->
            <?php include_once 'vacancy/.conf.php'; ?>
            <iframe id="iframe-job-module" frameborder="0" scrolling="no" width="100%" height="100%" src="/vacancy/resume_list.php?token=<?=$GLOBALS['token']?>"></iframe>
            <!-- ФРЕЙМА ВЫВОДА / КОНЕЦ -->
        </div>
    </body>
</html>