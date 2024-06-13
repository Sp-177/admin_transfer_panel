function transfer(src,dst,a){
    const s=document.getElementById(src);
    const d=document.getElementById(dst);
    const source=Array.from((a==='a')?s.options:s.selectedOptions);
    for(let i=0;i<source.length;i++){
        d.appendChild(source[i]);
    }
}
function fH(ct){
    const xhr = new XMLHttpRequest();
        xhr.open("GET", "?crop_type=" + ct, true);
        xhr.onload = function() {
            if (this.status === 200) {
                
                document.getElementById('hqlist1').innerHTML = this.responseText;
                document.getElementById('hqlist2').innerHTML = this.responseText;
            }
        }
        
        xhr.send();
        
}
function fHq(hqid){
    fU(hqid,document.getElementById('role').value);
}
function fr(roleid){
    fU(document.getElementById('hqlist1').value,roleid);
}
function fU(hqid,roleid){
    if(hqid!=='Headquarter'&&roleid!='Role'){
    const xhr = new XMLHttpRequest();
    const ct=document.getElementById('crop-type').value;
    xhr.open("GET", "?hqid=" + hqid + "&roleid=" + roleid+"&ct="+ct, true);
        xhr.onload = function() {
            if (this.status === 200) {
                console.log((this.responseText));
                document.getElementById('from-list').innerHTML = this.responseText;
                
            }
        }
        xhr.send();}
}
function search(input,id){
    const lists=document.getElementById(id);
    const array =input.split(',');
    const store=[];
    for(var j=0;j<array.length;j++){
 
    for(var i=0;i<lists.childElementCount;i++){
     let string1=lists.children[i].value;
     let string2=lists.children[i].textContent;
     if(string1.toUpperCase().indexOf(input.toUpperCase())==-1 && string2.toUpperCase().indexOf(input.toUpperCase())==-1 && store.includes(i)==false){
         lists.children[i].style.display='none';
     }
     else{
         lists.children[i].style.display='';
     }
    }}
} 