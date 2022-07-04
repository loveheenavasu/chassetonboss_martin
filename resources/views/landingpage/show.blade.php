{!! $content !!}
<script type="text/javascript">
    var x = document.getElementsByClassName("page")[0];
    x.id="page-content"
    function get_query(){
        var url = document.location.href;
        var qs = url.substring(url.indexOf('?') + 1).split('&');
        for(var i = 0, result = {}; i < qs.length; i++){
            qs[i] = qs[i].split('=');
            result[qs[i][0]] = decodeURIComponent(qs[i][1]);
        }
        return result;
    }
    var mainURL = window.location.href;
    var Obj = get_query();
    var contentDom = document.getElementById("page-content");
    var contentHtml = document.getElementById("page-content").innerHTML;
    var RE = new RegExp(Object.keys(Obj).join("|"),"g");
    contentDom.innerHTML = contentHtml.replaceAll(RE, function(matched) {
        return Obj[matched];
    });
    let path = window.location.href.split('/')
    window.history.replaceState({}, document.title, "/" + path[3]);

</script>