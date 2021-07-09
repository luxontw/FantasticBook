# FantasticBook
#### 註冊功能，登入後才可查看使用者資訊 & 發布貼文   
`POST /api/register.php`  

`POST /api/login.php` 

`GET /api/user_info.php?id=`
#### 貼文新增 / 刪除 / 查詢 / 修改功能
`POST /api/add_post.php` 

`POST /api/delete_post.php` 

`POST /api/post.php?id=` 

`POST /api/update_post.php`
#### 動態時報：顯示某個人的所有貼文
`GET /api/all_post.php?user_id=`
#### 貼文留言功能
`POST /api/add_comment.php` 

`POST /api/all_comment.php?post_id=` 
### Request Example	
`GET /api/all_post.php`
### Response Example	
```javascript    	
[	
 {	
    "id": "55",	
    "user_id": "2",	
    "title": "Quick Brown Fox",	
    "text": "The quick brown fox jumps over the lazy dog.",	
    "date_created": "2021-07-03 03:03:52",	
    "date_updated": "0000-00-00 00:00:00"	
  },	
  {	
    "id": "56",	
    "user_id": "1",	
    "title": "Brown Fox",	
    "text": "Fox jumps over the lazy dog.",	
    "date_created": "2021-07-03 08:24:14",	
    "date_updated": "0000-00-00 00:00:00"	
  }	
]
```
```javascript	
{	
  "id": "55",	
  "user_id": "2",	
  "title": "Quick Brown Fox",	
  "text": "The quick brown fox jumps over the lazy dog.",	
  "date_created": "2021-07-03 03:03:52",	
  "date_updated": "0000-00-00 00:00:00"	
}
```
```javascript	
{	
  "message": "No post found."
}
```
