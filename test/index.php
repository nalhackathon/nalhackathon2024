<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>わがままカードオンライン</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- タイトル画面 -->
    <div id="title-screen" style="text-align: center; padding-top: 100px;">
        <h1>わがままカードオンライン</h1>
        <div>
            <label for="username">ユーザー名:</label>
            <input type="text" id="username" name="username" placeholder="ユーザー名を入力してください" required>
        </div>
        <br>
        <button id="start-game" onclick="startGame()">ゲーム開始</button>
    </div>

    <!-- ロビー画面 -->
    <div id="lobby-screen" style="display: none; text-align: center; padding-top: 50px;">
        <button id="back-to-title" style="position: absolute; top: 20px; left: 20px;" onclick="backToTitle()">タイトルに戻る</button>
        <h1>ロビー画面</h1>
        <button id="create-room" onclick="showCreateRoom()">ルーム作成</button>
        <button id="join-room" onclick="showJoinRoom()">ルーム参加</button>
    </div>

    <!-- ルーム作成画面 -->
    <div id="create-room-screen" style="display: none; text-align: center; padding-top: 50px;">
        <button id="back-to-lobby" style="position: absolute; top: 20px; left: 20px;" onclick="backToLobby()">ロビーへ戻る</button>
        <h1>ルーム作成</h1>
        <p id="display-username"></p>
        <div>
            <label for="roomID">ルームID:</label>
            <input type="text" id="roomID" name="roomID" placeholder="ルームIDを入力してください" required>
        </div>
        <br>
        <button onclick="createRoom()">ルーム作成</button>
    </div>

    <!-- ルーム参加画面 -->
    <div id="join-room-screen" style="display: none; text-align: center; padding-top: 50px;">
        <button id="back-to-lobby" style="position: absolute; top: 20px; left: 20px;" onclick="backToLobby()">ロビーへ戻る</button>
        <h1>ルーム参加</h1>
        <div id="room-list"></div>
    </div>

    <!-- ルーム画面 -->
    <div id="room-screen" style="display: none; text-align: center; padding-top: 50px;">
        <h1>ルーム画面</h1>
        <p id="room-id-display"></p>
        <div id="participants-list">
            <h3>参加者:</h3>
            <ul id="participants"></ul>
        </div>
        <br>
        <button onclick="dissolveRoom()">ルーム解散</button>
        <button onclick="startGameInRoom()">ゲームスタート</button>
    </div>

    <script>
        let username = "";
        let currentRoomID = "";

        function startGame() {
            username = document.getElementById('username').value;
            if (username) {
                document.getElementById('title-screen').style.display = 'none';
                document.getElementById('lobby-screen').style.display = 'block';
            } else {
                alert('ユーザー名を入力してください。');
            }
        }

        function backToTitle() {
            document.getElementById('lobby-screen').style.display = 'none';
            document.getElementById('title-screen').style.display = 'block';
        }

        function showCreateRoom() {
            document.getElementById('lobby-screen').style.display = 'none';
            document.getElementById('create-room-screen').style.display = 'block';
            document.getElementById('display-username').textContent = `ユーザー名: ${username}`;
        }

        function showJoinRoom() {
            document.getElementById('lobby-screen').style.display = 'none';
            document.getElementById('join-room-screen').style.display = 'block';
            fetchRoomList();
        }

        function backToLobby() {
            document.getElementById('create-room-screen').style.display = 'none';
            document.getElementById('join-room-screen').style.display = 'none';
            document.getElementById('lobby-screen').style.display = 'block';
        }

        function createRoom() {
            const roomID = document.getElementById('roomID').value;
            if (roomID) {
                fetch('create_room.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ roomID, creater: username })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentRoomID = roomID;
                        document.getElementById('create-room-screen').style.display = 'none';
                        document.getElementById('room-screen').style.display = 'block';
                        document.getElementById('room-id-display').textContent = `ルームID: ${roomID}`;
                        fetchParticipants();
                    } else {
                        alert('ルーム作成に失敗しました');
                    }
                })
                .catch(error => console.error('Error:', error));
            } else {
                alert('ルームIDを入力してください。');
            }
        }

        function fetchRoomList() {
            fetch('fetch_rooms.php')
                .then(response => response.json())
                .then(data => {
                    const roomList = document.getElementById('room-list');
                    roomList.innerHTML = '';
                    data.rooms.forEach(room => {
                        const roomDiv = document.createElement('div');
                        roomDiv.innerHTML = `
                            <p>ルームID: ${room.roomID} | 作成者: ${room.creater}</p>
                            <button onclick="joinRoom('${room.roomID}')">参加する</button>
                        `;
                        roomList.appendChild(roomDiv);
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        function joinRoom(roomID) {
            fetch('join_room.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ roomID, participant: username })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentRoomID = roomID;
                    document.getElementById('join-room-screen').style.display = 'none';
                    document.getElementById('room-screen').style.display = 'block';
                    document.getElementById('room-id-display').textContent = `ルームID: ${roomID}`;
                    fetchParticipants();
                } else {
                    alert('ルーム参加に失敗しました');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function dissolveRoom() {
            fetch('dissolve_room.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ roomID: currentRoomID })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('ルームが解散されました');
                    document.getElementById('room-screen').style.display = 'none';
                    document.getElementById('lobby-screen').style.display = 'block';
                } else {
                    alert('ルーム解散に失敗しました');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function fetchParticipants() {
            fetch(`fetch_participants.php?roomID=${currentRoomID}`)
                .then(response => response.json())
                .then(data => {
                    const participantsList = document.getElementById('participants');
                    participantsList.innerHTML = '';
                    data.participants.forEach(participant => {
                        const li = document.createElement('li');
                        li.textContent = participant;
                        participantsList.appendChild(li);
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        function startGameInRoom() {
            alert('ゲームが開始されます！');
        }
    </script>
</body>
</html>
