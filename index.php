<?php
// config.php
$servername = "127.0.0.1:3333"; // Change this to your server name
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "unnati_db"; // Change this to your database name

function fetchHeadquarters($conn, $ct) {
    $sql = "SELECT id, name FROM master_headquarters WHERE crop_type='$ct'";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
     
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
            
        }$conn->close();
        
    } else {
        echo "No results found";$conn->close();
    }

    
    
}
if(isset($_GET['crop_type'])) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $crop_type = $_GET['crop_type'];
    fetchHeadquarters($conn, $crop_type);
    $conn->close();
}

function fetchUsers($conn, $hqid,$roleid,$ct){
    $sql = "SELECT id, name FROM master_users WHERE role_id = '$roleid' ";

    if ($ct == 'FC') {
        $sql .= "AND fc_hq_id = '$hqid'";
    } else {
        $sql .= "AND vc_hq_id = '$hqid'";
    }
    

    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
     
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
            
        }$conn->close();
    } else {
        echo "No results found";$conn->close();
    }
    $conn->close();
    
}
if(isset($_GET['hqid']) && isset($_GET['roleid']) && isset($_GET['ct'])) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $hqid = $_GET['hqid'];
    $roleid = $_GET['roleid'];
    $ct=$_GET['ct'];
    fetchUsers($conn, $hqid, $roleid, $ct);
    $conn->close();
    
}

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.container {
    width: 100%;
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.canvas {
    width: 1200px;
    height: 600px;
    background-color: red;
    padding: 20px;
    border-radius: 50px;
    display: flex;
    justify-content: space-around;
    align-items: center;
}

.from, .to {
    width: 50%;
    height: 570px;
    padding: 10px;
    background-color: wheat;
    border-radius: 30px;
    margin: 10px;
    display: flex;
    flex-direction: column;
    align-content: center;
    justify-content: space-evenly;
}

.header {
    display: flex;
    background-color: aqua;
    border-radius: 30px;
    justify-content: space-evenly;
    padding: 10px;
    flex-wrap: wrap;
}

.drop-1, .drop-2, .fixed {
    border: none;
    border-radius: 10px;
    margin: 10px;
    padding: 4px;
}

.fixed {
    background-color: yellow;
}

.search-bar {
    display: flex;
    margin: 10px;
}

.search-bar-input {
    flex: 1;
    border-radius: 10px 0 0 10px;
    padding: 4px;
    
}

.search-bar-input:focus {
    outline: none;
}

.search-button {
    cursor: pointer;
    width: 30px;
    background-color: green;
    
    border-radius: 0 10px 10px 0;
    opacity: 0.8;
}

.search-button:hover {
    opacity: 1;
}

.search-button:active {
    transform: scale(0.97);
    border: none;
}

.transfer-button {
    display: flex;
    flex-direction: column;
}

.transfer-button button {
    outline: none;
    border: none;
    padding: 10px;
    margin: 2px;
    cursor: pointer;
    border-radius: 10px;
    text-align: center;
    background-color: green;
    opacity: 0.8;
}

.transfer-button button:active {
    transform: scale(0.96);
}

.transfer-button button:hover {
    opacity: 1;
}

.output{
    display: flex;
    flex-direction: column;
    width: 500px;
    height: 600px;
    margin: 10px;
    padding:10px;
    border-radius:30px;
    background-color:white
    

}
.list-elements{
    width: 100%;
    height: 100%;
    border-radius:30px;
    padding:10px;
    
}
.list-elements option{
    margin: 10px;
    border:1px solid black;
    border-radius:10px;
    padding: 10px;;
}
.submit-button{
    margin: 10px; padding:10px; border-radius:20px;cursor:pointer;font-size: x-large; background-color:green;opacity: .8;
}
.submit-button:hover{
    opacity: 1;
}
.submit-button:active{
    transform: scale(0.96);
}
/* Media Queries for Responsiveness */
@media (max-width: 1200px) {
    .canvas {
        flex-direction: column;
        width: 95%;
    }

    .from, .to {
        width: 90%;
        margin: 10px 0;
    }

    .output {
        width: 80%;
        height: auto;
    }
}

@media (max-width: 768px) {
    .header {
        flex-direction: column;
        align-items: center;
    }

    .search-bar {
        flex-direction: column;
        align-items: center;
    }

    .search-bar-input {
        border-radius: 10px 10px 0 0;
    }

    .search-button {
        border-radius: 0 0 10px 10px;
        width: 100%;
    }

    .output {
        width: 90%;
        height: auto;
    }
}

@media (max-width: 480px) {
    .canvas {
        padding: 10px;
    }

    .from, .to {
        padding: 5px;
    }

    .header, .drop-1, .drop-2, .fixed {
        padding: 2px;
        margin: 5px;
    }

    .search-bar-input, .search-button {
        padding: 2px;
    }

    .output {
        width: 95%;
        height: auto;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <div class="canvas">
            <div class="from">
                <div class="header">
                    <div class="fixed"><p>FROM</p></div>
                    <div class="search-bar">
                        <input onkeypress="search(value,'hq-list')" class="search-bar-input" type="text" placeholder="Search...">
                        <button class="search-button"><img style="width: 17px;" src="search.png"></button>
                    </div>
                    <div style="width: auto;">
                        <select id="hqlist1"class="drop-1" onchange="fHq(this.value)">
                         <option >Headquarter</option>
                        </select>
                    </div>
                    <div style="width:auto;">
                        <select id="role" class="drop-2" onchange="fr(this.value)">
                        <option >Role</option>
                            <option value="4">TSM</option>
                            <option value="5">MDO</option>
                            <option value="11">RET</option>
                            <option value="10">DRT</option>
                        </select>
                    </div>
                    <div style="width:auto;">
                        <select id="crop-type"class="drop-2" onchange="fH(this.value)">
                            <option  >CropType</option>
                            <option value="FC">FC</option>
                            <option value="VC">VC</option>
                        </select>
                    </div>
                </div>
                <div class="output">
                    <select id="from-list" class="list-elements" multiple>
                    </select>
                    <div class="search-bar">
                        <input onkeypress="search()" class="search-bar-input" type="text" placeholder="Search...">
                        <button class="search-button"><img style="width: 17px;" src="search.png"></button>
                    </div>
                    <p style="font-size: 15px;margin-left: 10px;color:gray;">( / or , or  -  or ' ' separeted values)</p>
                </div>
            </div>
            <div class="transfer-button">
                <button onclick="transfer('from-list','to-list','a')">>></button>
                <button onclick="transfer('from-list' ,'to-list' ,'n')">></button>
                <button onclick="transfer('to-list' ,'from-list' ,'n')"><</button>
                <button onclick="transfer('to-list' ,'from-list' ,'a')"><<</button>
            </div>
            <div class="to">
                <div class="header">
                    <div class="fixed"><p>TO</p></div>
                    <div class="search-bar">
                        <input class="search-bar-input" type="text" placeholder="Search...">
                        <button class="search-button"><img style="width: 17px;" src="search.png"></button>
                    </div>
                    <div style="width:auto;">
                        <select id="hqlist2" class="drop-1">
                        <option >Headquarter</option>
                        </select>
                    </div>
                   
                </div>
                <div class="output">
                    <select id="to-list" class="list-elements" multiple></select>
                    <div class="search-bar">
                        <input onkeypress="search()" class="search-bar-input" type="text" placeholder="Search...">
                        <button class="search-button"><img style="width: 17px;" src="search.png"></button>
                    </div>
                    <p style="font-size: 15px;margin-left: 10px;color:gray;">( / or , or  -  or ' ' separeted values)</p>
                </div>
            </div>
        </div>
        <button class="submit-button" >SUBMIT</button>
    </div>
    <script>
    
    
     
    
    
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
   
}
    </script>
</body>
</html>
