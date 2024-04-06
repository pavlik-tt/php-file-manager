# php-file-manager
HTTP file manager for PHP

Can send, delete, modify, and receive files.

## Usage
> ** Note: The path will start with $uploads_path regardless of whether the specified path is relative. **
### Listing all files in directory
```
GET <your server>/main.php?path=<directory path>
```
### Uploading files
```
GET <your server>/main.php?action=upload
```
The server will return a form for uploading the file.
*or*
```
POST <your server>/main.php?path=<path of the uploaded file>
```
### Downloading files
```
GET <your server>/main.php?action=download&path=<path to your file>
```
The server will return the file size in bytes in the [`Content-Length`](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Length) header, the file type in the [`Content-Type`](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Type) header, and the file itself in the response body.
### Deleting files
```
DELETE <your server>/main.php?path=<path to your file>
```
*or*
```
GET <your server>/main.php?action=delete&path=<path to your file>