<?php

class User {
    private $user;
    private $con;

    public function __construct($con, $id) {
        $this->con = $con;

        $userInfo = mysqli_query($con, "SELECT * FROM user WHERE id='$id'");
        $this->user  = mysqli_fetch_array($userInfo);
    }

    public function getId() {
        $user = $this->user;
        return $user['id'];
    } 

    public function isClosed() {
        $user = $this->user;
        return $user['is_closed'] == 0 ? false : true; 
    }

    public function getFirstAndLastName() {
        $user = $this->user;
        return $user['first_name']." ".$user['last_name'];
    }

    public function getProfilePic() {
        $user = $this->user;
        return $user['avatar'];
    }

    public function isFriend($userId) {
        $user = $this->user;
        $friendString = $user['friend_array'];
        $friendArray = explode(',', $friendString);
        return in_array($userId, $friendArray);
    }

    public function getFriendListText() {
        $user = $this->user;
        return $user['friend_array'];
    }

    public function sendFriendRequest($userTo) {
        $user = $this->user;
        $userId = $user['id'];

        $sql = "INSERT INTO friend_request(from_user, to_user) VALUES ({$userId}, {$userTo})";
        $query = mysqli_query($this->con, $sql);
    }

    public function didReceiveFriendRequest($userTo) {
        $user = $this->user;
        $userId = $user['id'];

        $sql = "SELECT * FROM friend_request WHERE from_user = '$userId' AND to_user = '$userTo'";
        $query = mysqli_query($this->con, $sql);

        if(mysqli_num_rows($query) >0) {
            return true;
        }
        
        return false;
    }

    public function cancelFriendRequest($userTo) {
        $user = $this->user;
        $userId = $user['id'];

        $sql = "DELETE FROM friend_request WHERE from_user = '$userId' AND to_user = '$userTo'";
        $query = mysqli_query($this->con, $sql);
    }

    public function acceptFriendRequest($userTo) {
        $user = $this->user;
        $userId = $user['id'];

        $sql = "DELETE FROM friend_request WHERE from_user = '$userId' AND to_user = '$userTo'";
        $query = mysqli_query($this->con, $sql);

        $friendList = $user['friend_array'];
        $friendList = $friendList.",".$userTo;

        $sql = "UPDATE user SET friend_array = '$friendList' WHERE id = '$userId'";
        $query = mysqli_query($this->con, $sql);
    }

    public function removeFriend($removeUserId) {
        $user = $this->user;
        $userId = $user['id'];

        $friendListArray = explode(",",$user['friend_array']);
        foreach($friendListArray as $key => $value) {
            if($value == $removeUserId) {
                unset($friendListArray[$key]);
            }
        }

        $insertDBFriendListText = implode(",", $friendListArray);

        $sql = "UPDATE user SET friend_array = '$insertDBFriendListText' WHERE id = '$userId'";
        $query = mysqli_query($this->con, $sql);
    }

    public function countMutualFriend($userId) {
        $user = $this->user;
        $friendListArray = explode(",", $user['friend_array']);

        $checkUserObj = new User($this->con, $userId);
        $checkUser = $checkUserObj->user;
        $checkUserFriendListArray = explode(",", $checkUser['friend_array']);

        $count = 0;

        foreach($checkUserFriendListArray as $i) {
            foreach($friendListArray as $j) {
                if($i == $j) {
                    $count++;
                }
            }
        }

        return $count;
    } 

    public function getEditableUserInfo() {
        $user = $this->user;

        $data['first_name'] = $user['first_name'];
        $data['last_name'] = $user['last_name'];
        $data['sex'] = $user['sex'];
        $data['birthday'] = $user['birthday'];

        return $data;
    }

    public function changePassword($newPassword) {
        $userId = $this->getId();
        $newPassword = md5($newPassword);

        $sql = "UPDATE user SET password = '$newPassword' WHERE id = '$userId'";
        $query = mysqli_query($this->con, $sql);

        if($query) {
            return true;
        }

        return false;
    }

    public function closeAccount() {
        $userId = $this->getId();
        $sql = "UPDATE user SET is_closed = 1 WHERE id = '$userId'";
        $query = mysqli_query($this->con, $sql);

        if($query) {
            return true;
        }

        return false;
    }

    public function openAccount() {
        $userId = $this->getId();
        $sql = "UPDATE user SET is_closed = 0 WHERE id = '$userId'";
        $query = mysqli_query($this->con, $sql);

        if($query) {
            return true;
        }

        return false;
    }

    public function editProfile($firstName, $lastName, $sex, $birthday) {
        $userId = $this->getId();
        $sql = "UPDATE user SET
                first_name = '$firstName',
                last_name = '$lastName',
                sex = '$sex',
                birthday = '$birthday'
                WHERE id = '$userId'";

        $query = mysqli_query($this->con, $sql);

        if($query) {
            return true;
        }

        return false;
    }

    public function changeAvatar($image) {
        $userId = $this->getId();

        $sql = "UPDATE user SET avatar = '$image' WHERE id = '$userId'";
        $query = mysqli_query($this->con, $sql);

        if($query) {
            return true;
        }

        return false;
    }
}