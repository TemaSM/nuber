
<?= $this->renderShared('instance-header') ?>
<link rel="stylesheet" href="/css/xterm.css">
<script src="/js/xterm.js"></script>
<style>
  

   /* #terminal-container {
        width: 720px;
        height: 420px;
        background: black;
     
    }
    #terminal .terminal-error {
        color:green;
    }*/
    .terminal {
        padding:10px;
    }
</style>

<div class="row">
    <div class="col-2">
        <?= $this->renderShared('instance-nav') ?>
    </div>
    <div class="col-10 console">

        <div id="terminal-container">
            <div id="terminal">
               
            </div>
        </div>

    </div>
</div>



<script>
    const term = new Terminal({
        cols: 80,
        rows: 24,
        useStyle: true,
        screenKeys: false
    });

    const status = '<?= $status ?>';

    term.open(document.getElementById('terminal'));   

    function initializeTerm(){

        // LXD auth uses cert files, so websocket is in backend
        const server = new WebSocket('wss://<?= $this->request->host() ?>/?server=<?= $node . ':8443' . $path ?>');

        server.onopen = function(event) {
            console.log('websocket opened: ' +  'wss://<?= $this->request->host() ?>'); 

            term.write('\x1bc'); // clear       
            term.onData((data, encoding) =>  server.send(data));
            
            server.onmessage = function(message) {
                console.log(event);
                term.write(message.data)
            };

            server.onclose = function(event) {
                console.log('socket has been closed', event);
            };
        };
    }

    if(status === 'Running'){
        initializeTerm();
    }
    else{
        term.write('Instance is not running');
    }

</script>