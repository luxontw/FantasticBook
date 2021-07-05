# FantasticBook
API 測試完成，前端部分只串接了 `GET /api/all_post.php`
## 取得所有文章
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
## 取得單一文章
### Request Example
`GET /api/post.php?id=55`
### Response Example
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
### 無此文章
```javascript
"Post not found."
```
## 新增文章
### Request Example
`POST /api/add_post.php`
```javascript    
{
  "user_id": "2",
  "title": "Fox",
  "text": "Foxs jump over the lazy dog."
}
```
### Response Example
```javascript    
"Post created successfully."
```
### 無此使用者
```javascript
"User could not be found.""Post could not be created."
```
### 新增文章失敗
```javascript
"Post could not be created."
```
## 編輯文章
### Request Example
`POST /api/update_post.php`
```javascript    
{
  "id": "55",
  "user_id": "2",
  "title": "Brown",
  "text": "Fox jumps."
}
```
### Response Example
```javascript    
"Post updated."
```
### 編輯者非文章作者或無此文章
```javascript
"Post could not be updated."
```
## 刪除文章
### Request Example
`DELETE /api/delete_post.php`
```javascript    
{
  "id": "55",
  "user_id": "2"
 }
```
### Response Example
```javascript    
"Post deleted."
```
### 刪除者非文章作者或無此文章
```javascript
"Post could not be deleted"
```
