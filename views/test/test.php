
<button>click</button>

<div id="flash-messages" class="flash-message-div"></div>

<script type="module">

    import flash from "./public/JS/flashmessages/flash.js";

    document.querySelector('button').addEventListener('click', () => {
        flash.showMessage('success', 'This is a success message');

    });




</script>