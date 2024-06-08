
function transfer(f,a){
const from_sec=document.getElementById("from-list");
const to_sec=document.getElementById("to-list");
const selected_from=document.getElementsByClassName("check");
const selected_to=document.getElementsByClassName("check");
if(f==='f'){
        for(var i=0;i<selected_from.length;i++){
            if(from_sec.contains(selected_from[i])&&(selected_from[i].checked || a==='a' )){
                selected_from[i].checked=false;
                const sec=from_sec.children[i];
                to_sec.appendChild(sec);
                
            }
        }

    }
else{
        
        for(var i=0;i<selected_to.length;i++){
            if(from_sec.contains(selected_to[i])&&(selected_to[i].checked || a==='a' )){
                const sec=document.getElementById(selected_to[i].value);
                selected_to[i].checked=false;
                from_sec.appendChild(sec);
                
            }
        }
    }

}
