
function transfer(src,dst,a){
    const s=document.getElementById(src);
    const d=document.getElementById(dst);
    const source=Array.from((a==='a')?s.options:s.selectedOptions);
    for(let i=0;i<source.length;i++){
        d.appendChild(source[i]);
    }
}
function search(){
    
}