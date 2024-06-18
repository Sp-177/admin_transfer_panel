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
        }
    } else {
        echo "<option>No results found</option>";
    }
    $conn->close();
}

if (isset($_GET['crop_type'])) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $crop_type = $_GET['crop_type'];
    fetchHeadquarters($conn, $crop_type);
    $conn->close();
}

function fetchUsers($conn, $hqid, $roleid, $ct) {
    $column = $ct == 'FC' ? 'fc_hq_id' : 'vc_hq_id';
    $sql = "SELECT id, name,mobile FROM master_users WHERE role_id='$roleid' AND $column='$hqid'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row["id"] . "'>" . $row["name"]." | ". $row["mobile"] . "</option>";
        }
    } else {
        echo "<option>No results found</option>";
    }
    $conn->close();
}

if (isset($_GET['hqid']) && isset($_GET['roleid']) && isset($_GET['ct'])) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $hqid = $_GET['hqid'];
    $roleid = $_GET['roleid'];
    $ct = $_GET['ct'];
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
            width: 75rem;
            height: 37.5rem;
            background-color: red;
            padding: 1.25rem;
            border-radius: 3.125rem;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .from, .to {
            width: 50%;
            height: 35.625rem;
            padding: 0.625rem;
            background-color: wheat;
            border-radius: 1.875rem;
            margin: 0.625rem;
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
        }

        .header {
            display: flex;
            background-color: aqua;
            border-radius: 1.875rem;
            justify-content: space-evenly;
            padding: 0.625rem;
            flex-wrap: wrap;
        }

        .drop-1, .drop-2, .fixed {
            border: none;
            border-radius: 0.625rem;
            margin: 0.625rem;
            padding: 0.25rem;
        }

        .fixed {
            background-color: yellow;
        }

        .search-bar {
            display: flex;
            margin: 0.625rem;
            background-color: white;
            border-radius: 0.625rem;
        }

        .search-bar-input {
            flex: auto;
            padding: 0.25rem 1.25rem 0.25rem 0.25rem;
            border-radius: 0.625rem;
            background: url('search.png') no-repeat 98% 50%;
            background-size: 0.9375rem;
        }

        .search-bar-input:focus {
            outline: none;
        }

        .transfer-button {
            display: flex;
            flex-direction: column;
        }

        .transfer-button button {
            outline: none;
            border: none;
            padding: 0.625rem;
            margin: 0.125rem;
            cursor: pointer;
            border-radius: 0.625rem;
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

        .output {
            display: flex;
            flex-direction: column;
            width: 31.25rem;
            height: 37.5rem;
            margin: 0.625rem;
            padding: 0.625rem;
            border-radius: 1.875rem;
            background-color: white;
        }

        .list-elements {
            width: 100%;
            height: 100%;
            border-radius: 1.875rem;
            padding: 0.625rem;
        }

        .list-elements option {
            margin: 0.625rem;
            border: 1px solid black;
            border-radius: 0.625rem;
            padding: 0.625rem;
        }

        .submit-button {
            margin: 0.625rem;
            padding: 0.625rem;
            border-radius: 1.25rem;
            cursor: pointer;
            font-size: large;
            background-color: green;
            opacity: 0.8;
        }

        .submit-button:hover {
            opacity: 1;
        }

        .submit-button:active {
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
                        <input onkeyup="search(this.value,'hqlist1')" class="search-bar-input" type="text" placeholder="Search...">
                    </div>
                    <div>
                        <select id="hqlist1" class="drop-1" onchange="fHq(this.value,'hqlist1')">
                            <option>Headquarter</option>
                        </select>
                    </div>
                    <div>
                        <select id="role" class="drop-2" onchange="fr(this.value,'role')">
                            <option>Role</option>
                            <option value="4">TSM</option>
                            <option value="5">MDO</option>
                            <option value="11">RET</option>
                            <option value="10">DRT</option>
                        </select>
                    </div>
                    <div>
                        <select id="crop-type" class="drop-2" onchange="fH(this.value)">
                            <option>CropType</option>
                            <option value="FC">FC</option>
                            <option value="VC">VC</option>
                        </select>
                    </div>
                </div>
                <div class="output">
                    <select id="from-list" class="list-elements" multiple></select>
                    <div class="search-bar">
                        <input onkeyup="search(this.value,'from-list')" class="search-bar-input" type="text" placeholder="Search...">
                    </div>
                    <p style="font-size: 0.9375rem; margin-left: 0.625rem; color: gray;">( / or , or - or ' ' separated values)</p>
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
                        <input class="search-bar-input" onkeyup="search(this.value,'hqlist2')" type="text" placeholder="Search...">
                    </div>
                    <div>
                        <select id="hqlist2" class="drop-1"onchange="fHq(this.value,'hqlist2')">
                            <option>Headquarter</option>
                        </select>
                    </div>
                    <div>
                        <select id="role1" class="drop-2" onchange="fr(this.value,'role1')">
                            <option>Role</option>
                            <option value="4">TSM</option>
                            <option value="5">MDO</option>
                            <option value="11">RET</option>
                            <option value="10">DRT</option>
                        </select>
                    </div>
                </div>
                <div class="output">
                    <select id="to-list" class="list-elements" multiple></select>
                    <div class="search-bar">
                        <input onkeyup="search(this.value,'to-list')" class="search-bar-input" type="text" placeholder="Search...">
                    </div>
                    <p style="font-size: 0.9375rem; margin-left: 0.625rem; color: gray;">( / or , or - or ' ' separated values)</p>
                </div>
            </div>
        </div>
        <button class="submit-button">SUBMIT</button>
    </div>
    <script>
        function transfer(src, dst, all) {
            const source = document.getElementById(src);
            const destination = document.getElementById(dst);
            
            const items = Array.from(all === 'a'? Array.from(source.options).filter(option => option.style.display !== 'none'): source.selectedOptions);
            items.forEach(item => destination.appendChild(item));
        }

        function fH(ct) {
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

        function fHq(hqid,input) {
            
            let output =(input==='hqlist1'?'from-list':'to-list');
            let roleid=(input==='hqlist1'?'role':'role1')
            fU(hqid, document.getElementById(roleid).value,output);
        }

        function fr(roleid,input) {
            if(roleid==='4'||roleid==='5'){
                let opp=(input==='role'?'role1':'role');
                let options=document.getElementById(opp);
                Object.values(options.options).forEach((item,index)=>{
                    if(item.value==='10'||item.value==='11'){
                        item.disabled=true;
                        item.style.display='none';
                    }
                    else{
                        item.disabled=false;
                        item.style.display='';
                    }
                });
            }
            else if(roleid==='10'||roleid==='11'){
                let opp=(input==='role'?'role1':'role');
                let options=document.getElementById(opp);
                Object.values(options.options).forEach((item,index)=>{
                    if(item.value==='4'||item.value==='5'){
                        item.disabled=true;
                        item.style.display='none';
                    }
                    else{
                        item.disabled=false;
                        item.style.display='';
                    }
                });
            }
           else{
            let opp=(input==='role'?'role1':'role');
                let options=document.getElementById(opp);
                Object.values(options.options).forEach((item,index)=>{
                    
                        item.disabled=false;
                        item.style.display='';
                    
                });
           }
            let output =(input==='role'?'from-list':'to-list');
            let hqid=(input==='role'?'hqlist1':'hqlist2');
            fU(document.getElementById(hqid).value, roleid,output);
        }

        function fU(hqid, roleid,output) {
            
            if (hqid !== 'Headquarter' && roleid !== 'Role') {
                const xhr = new XMLHttpRequest();
                const ct = document.getElementById('crop-type').value;
                xhr.open("GET", "?hqid=" + hqid + "&roleid=" + roleid + "&ct=" + ct, true);
                xhr.onload = function() {
                    if (this.status === 200) {
                        document.getElementById(output).innerHTML = this.responseText;
                    }
                }
                xhr.send();
            }
        }

        function search(input, id) {
            const list = document.getElementById(id);
            const terms = input.split(/[,/\s-]+/).map(term => term.trim().toUpperCase());
            const options = Array.from(list.options);
            options.forEach(option => {
                const text = option.textContent.toUpperCase();
                const value = option.value.toUpperCase();
                const match = terms.some(term => text.includes(term) || value.includes(term));
                option.style.display = match ? '' : 'none';
            });
        }
    </script>
</body>
</html>
