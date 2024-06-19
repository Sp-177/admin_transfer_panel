<?php
$servername = "127.0.0.1:3333";
$username   = "root";
$password   = "";
$dbname     = "unnati_db";

// Function to establish mysqli connection
function connectDatabase($servername, $username, $password, $dbname) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to fetch headquarters options based on crop type
function fetchHeadquarters($conn, $crop_type) {
    $stmt = $conn->prepare("SELECT id, name FROM master_headquarters WHERE crop_type = ?");
    $stmt->bind_param("s", $crop_type);
    $stmt->execute();
    $result = $stmt->get_result();
    $options = "<option value=''>Headquarter</option>";
    while ($row = $result->fetch_assoc()) {
        $options .= "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
    }
    $stmt->close();
    echo $options; // Echo options to send back as response
}

// Function to fetch users based on headquarters ID, role ID, and crop type
function fetchUsers($conn, $hqid, $roleid, $crop_type) {
    $column = ($crop_type == 'FC') ? 'fc_hq_id' : 'vc_hq_id';
    $stmt = $conn->prepare("SELECT id, name, mobile FROM master_users WHERE role_id = ? AND $column = ?");
    $stmt->bind_param("ii", $roleid, $hqid);
    $stmt->execute();
    $result = $stmt->get_result();
    $options = "";
    while ($row = $result->fetch_assoc()) {
        $options .= "<option value='" . $row["id"] . "'>" . $row["name"] . " | " . $row["mobile"] . "</option>";
    }
    $stmt->close();
    echo $options; // Echo options to send back as response
}

// Handle GET requests
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['crop_type'])) {
        $conn = connectDatabase($servername, $username, $password, $dbname);
        $crop_type = $_GET['crop_type'];
        fetchHeadquarters($conn, $crop_type);
        $conn->close();
        exit();
    } elseif (isset($_GET['hqid'], $_GET['roleid'], $_GET['ct'])) {
        $conn = connectDatabase($servername, $username, $password, $dbname);
        $hqid      = $_GET['hqid'];
        $roleid    = $_GET['roleid'];
        $crop_type = $_GET['ct'];
        fetchUsers($conn, $hqid, $roleid, $crop_type);
        $conn->close();
        exit();
    }
}

// Function to insert data into master_logs table
function postIntoLog($conn, $id) {
    // Generate log_id and other necessary data for insertion
    $sql = "INSERT INTO master_logs (log_id, user_id, role_id, deal_in_crop, fc_hq_id, vc_hq_id, fc_hq_sub_id, vc_hq_sub_id, prev_log_id)
            SELECT 
                CONCAT(
                    COALESCE(fc_hq_id, 0), '-', 
                    COALESCE(vc_hq_id, 0), '-', 
                    id, '-', 
                    role_id, 
                    '-', 
                    IFNULL(SUBSTRING_INDEX(log_id, '-', -1) + 1, 1)
                ) AS log_id,
                id,
                role_id,
                deal_in_crop,
                fc_hq_id,
                vc_hq_id,
                fc_hq_sub_id,
                vc_hq_sub_id,
                log_id AS prev_log_id
            FROM master_users
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Function to update user based on crop type and source ID
function updateUserBasedOnCropType($conn, $user_id, $src, $dst) {
    // Fetch crop_type based on src from master_headquarters
    $stmt = $conn->prepare("SELECT crop_type FROM master_headquarters WHERE id = ?");
    $stmt->bind_param("i", $src);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if (!$result) {
        echo "No crop_type found for the given src ID";
        return;
    }

    $crop_type = $result['crop_type'];
    $column_to_update = ($crop_type == 'FC') ? 'fc_hq_id' : 'vc_hq_id';

    // Update master_users table
    $sql = "UPDATE master_users 
            SET 
                log_id = (SELECT log_id FROM master_logs WHERE user_id = ? ORDER BY date_of_change DESC LIMIT 1),
                $column_to_update = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $user_id, $dst, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Update successful";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'insert' && isset($_REQUEST['id'])) {
        $conn = connectDatabase($servername, $username, $password, $dbname);
        $id = $_REQUEST['id'];
        postIntoLog($conn, $id);
        $conn->close();
        exit;
    } elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update' && isset($_REQUEST['id'], $_REQUEST['src'], $_REQUEST['dst'])) {
        $conn = connectDatabase($servername, $username, $password, $dbname);
        $id  = $_REQUEST['id'];
        $src = $_REQUEST['src'];
        $dst = $_REQUEST['dst'];
        updateUserBasedOnCropType($conn, $id, $src, $dst);
        $conn->close();
        exit;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Transfer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
            padding: 0.25rem 2.25rem 0.25rem 0.25rem;
            border-radius: 0.625rem;
            background: url('https://www.freepnglogos.com/uploads/search-png/search-icon-transparent-images-vector-23.png') no-repeat right center;
            background-size: 2.5rem;
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
            color: white;
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
            color: white;
            opacity: 0.8;
        }
        .submit-button:hover {
            opacity: 1;
        }
        .submit-button:active {
            transform: scale(0.96);
        }
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
            .search-bar-input {
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
            <!-- FROM Section -->
            <div class="from">
                <div class="header">
                    <div class="fixed"><p>FROM</p></div>
                    <!-- Search bar for headquarters -->
                    <div class="search-bar">
                        <input onkeyup="search(this.value, 'hqlist1')" class="search-bar-input" type="text" placeholder="Search...">
                    </div>
                    <!-- Dropdown for selecting headquarters -->
                    <div>
                        <select id="hqlist1" class="drop-1" onchange="fHq(this.value, 'hqlist1')">
                            <option>Headquarter</option>
                        </select>
                    </div>
                    <!-- Dropdown for selecting role -->
                    <div>
                        <select id="role" class="drop-2" onchange="fr(this.value, 'role')">
                            <option>Role</option>
                            <option value="4">TSM</option>
                            <option value="5">MDO</option>
                            <option value="11">RET</option>
                            <option value="10">DRT</option>
                        </select>
                    </div>
                    <!-- Dropdown for selecting crop type -->
                    <div>
                        <select id="crop-type" class="drop-2" onchange="fH(this.value, 'crop-type')">
                            <option>CropType</option>
                            <option value="FC">FC</option>
                            <option value="VC">VC</option>
                        </select>
                    </div>
                </div>
                <div class="output">
                    <!-- List of users/items -->
                    <select id="from-list" class="list-elements" multiple></select>
                    <!-- Search bar for users/items -->
                    <div class="search-bar">
                        <input onkeyup="search(this.value, 'from-list')" class="search-bar-input" type="text" placeholder="Search...">
                    </div>
                    <p style="font-size: 0.9375rem; margin-left: 0.625rem; color: gray;">
                        ( / or , or - or ' ' separated values)
                    </p>
                </div>
            </div>
            <!-- Transfer buttons -->
            <div class="transfer-button">
                <button onclick="transfer('from-list', 'to-list', 'a')">>></button>
                <button onclick="transfer('from-list', 'to-list', 'n')">></button>
                <button onclick="transfer('to-list', 'from-list', 'n')"><</button>
                <button onclick="transfer('to-list', 'from-list', 'a')"><<</button>
                <button onclick="undo()"><i class="fa fa-undo"></i></button>
            </div>
            <!-- TO Section -->
            <div class="to">
                <div class="header">
                    <div class="fixed"><p>TO</p></div>
                    <!-- Search bar for headquarters -->
                    <div class="search-bar">
                        <input class="search-bar-input" onkeyup="search(this.value, 'hqlist2')" type="text" placeholder="Search...">
                    </div>
                    <!-- Dropdown for selecting headquarters -->
                    <div>
                        <select id="hqlist2" class="drop-1" onchange="fHq(this.value, 'hqlist2')">
                            <option>Headquarter</option>
                        </select>
                    </div>
                    <!-- Dropdown for selecting role -->
                    <div>
                        <select id="role1" class="drop-2" onchange="fr(this.value, 'role1')">
                            <option>Role</option>
                            <option value="4">TSM</option>
                            <option value="5">MDO</option>
                            <option value="11">RET</option>
                            <option value="10">DRT</option>
                        </select>
                    </div>
                    <!-- Dropdown for selecting crop type -->
                    <div>
                        <select id="crop-type1" class="drop-2" onchange="fH(this.value, 'crop-type1')">
                            <option>CropType</option>
                            <option value="FC">FC</option>
                            <option value="VC">VC</option>
                        </select>
                    </div>
                </div>
                <div class="output">
                    <!-- List of users/items -->
                    <select id="to-list" class="list-elements" multiple></select>
                    <!-- Search bar for users/items -->
                    <div class="search-bar">
                        <input onkeyup="search(this.value, 'to-list')" class="search-bar-input" type="text" placeholder="Search...">
                    </div>
                    <p style="font-size: 0.9375rem; margin-left: 0.625rem; color: gray;">
                        ( / or , or - or ' ' separated values)
                    </p>
                </div>
            </div>
        </div>
        <!-- Submit button -->
        <button id='submit_button' class="submit-button" onclick="submit('submit_button')">SUBMIT</button>
    </div>

    <script>
        // Undo stack to store actions for undo functionality
        let undoStore = [];
        // HashMap to store transfer details
        let hashmap = new Map();

        // Undo function to revert the last transfer
        function undo() {
            if (undoStore.length > 0) {
                const undObj = undoStore.pop();
                const source = document.getElementById(undObj.src);
                const destination = document.getElementById(undObj.dst);
                undObj.items.forEach(item => {
                    hashmap.delete(item.value);
                    source.appendChild(item);
                });
            }
        }

        // Transfer function to move items between lists
        function transfer(src, dst, all) {
            const source = document.getElementById(src);
            const destination = document.getElementById(dst);
            const srcHq = document.getElementById((src === 'from-list' ? 'hqlist1' : 'hqlist2')).value;
            const dstHq = document.getElementById((dst === 'from-list' ? 'hqlist1' : 'hqlist2')).value;

            // Ensure required fields are selected
            if (document.getElementById('hqlist1').value === 'Headquarter' ||
                document.getElementById('hqlist2').value === 'Headquarter' ||
                document.getElementById('role').value === 'Role' ||
                document.getElementById('role1').value === 'Role') {
                return;
            }

            // Collect items to transfer
            const items = Array.from(all === 'a' ?
                Array.from(source.options).filter(option => option.style.display !== 'none') :
                source.selectedOptions);

            // Move items and update hashmap
            items.forEach(item => {
                hashmap.set(item.value, { 'src': srcHq, 'dst': dstHq });
                destination.appendChild(item);
            });

            // Store the action for undo
            const undObj = { 'src': src, 'dst': dst, 'items': items };
            undoStore.push(undObj);
        }

        // Function to fetch headquarters based on crop type
        function fH(cropType, input) {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "?crop_type=" + cropType, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    const selectElem = document.getElementById(input === 'crop-type' ? 'hqlist1' : 'hqlist2');
                    selectElem.innerHTML = this.responseText;
                }
            };
            xhr.send();
        }

        // Function to fetch users based on headquarters ID
        function fHq(hqid, input) {
            let output = input === 'hqlist1' ? 'from-list' : 'to-list';
            let roleid = input === 'hqlist1' ? 'role' : 'role1';
            fU(hqid, document.getElementById(roleid).value, output);
        }

        // Function to filter roles based on selection
        function fr(roleid, input) {
            const options = document.getElementById(input === 'role' ? 'role1' : 'role').options;
            Array.from(options).forEach(option => {
                if ((roleid === '4') && (option.value === '5' || option.value === '10' || option.value === '11')) {
                    option.disabled = true;
                    option.style.display = 'none';
                } else if ((roleid === '5') && (option.value === '10' || option.value === '11')) {
                    option.disabled = true;
                    option.style.display = 'none';
                } else if ((roleid === '10' || roleid === '11') && (option.value === '4' || option.value === '5')) {
                    option.disabled = true;
                    option.style.display = 'none';
                } else {
                    option.disabled = false;
                    option.style.display = '';
                }
            });

            let output = input === 'role' ? 'from-list' : 'to-list';
            let hqid = input === 'role' ? 'hqlist1' : 'hqlist2';
            fU(document.getElementById(hqid).value, roleid, output);
        }

        // Function to fetch users based on headquarters and role
        function fU(hqid, roleid, output) {
            if (hqid !== 'Headquarter' && roleid !== 'Role'&&hqid !==''&&roleid !=='') {
                const xhr = new XMLHttpRequest();
                const cropType = document.getElementById((output === 'to-list') ? 'crop-type1' : 'crop-type').value;
                xhr.open("GET", "?hqid=" + hqid + "&roleid=" + roleid + "&ct=" + cropType, true);
                xhr.onload = function() {
                    if (this.status === 200) {
                        const selectElem = document.getElementById(output);
                        selectElem.innerHTML = this.responseText;
                    }
                };
                xhr.send();
            }
        }

        // Function to search within lists
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

        // Function to submit the modifications
        function submit(input) {
            

            // Check if any modifications have been made
            if (hashmap.size === 0) {
                alert("No modifications have been made!!!");
                return;
            }

            // Change button text to indicate loading

            const submitButton = document.getElementById(input);
            submitButton.innerHTML='<i class="fa fa-circle-o-notch fa-spin"></i> LOADING';

            
            // Iterate over hashmap to send POST requests for each item
            

            const procesing=()=>{
            hashmap.forEach((item, index) => {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "index.php?action=insert&id=" + index, true);
                xhr.setRequestHeader("Content-Type", "application/json");

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Successful response, proceed with the next request
                        const xhr1 = new XMLHttpRequest();
                        xhr1.open("POST", "index.php?action=update&id=" + index + "&src=" + item.src + "&dst=" + item.dst, true);
                        xhr1.setRequestHeader("Content-Type", "application/json");

                        xhr1.onload = function() {
                            if (xhr1.status === 200) {
                                // Successfully processed second request
                                hashmap.delete(index);
                            } else {
                                alert("Server arguments error for second request!!!");
                            }
                        };

                        xhr1.onerror = function() {
                            alert("Error sending the second request!!!");
                        };

                        xhr1.send();
                    } else {
                        // Error in the first request, handle accordingly
                        alert("Error sending the first request!!!");
                    }
                };

                // Send the first request
                xhr.send();
            });

            // Update UI when all requests are complete
            
            submitButton.innerHTML = "SUBMIT";
            alert("Modification successfully");
            hashmap.clear();
            undoStore=[];}
            setTimeout(procesing,3000);
            
        }
    </script>
</body>
</html>
