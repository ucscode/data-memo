# data-memo
Data Memo is a light weight data saver that creates, modifies, retrieve or store data (to any user defined file) as json.

Not every data needs to be stored in database. Some data doesn't even require more than one row of information. Just like session, they may also require instant update at any point in time. It is not always the best option to personally edit those data when you can also do it programically. This is when Data Memo! comes in.

### Inclusion
``` require_once "path/to/data-memo.php"; ```

### How To Use
DataMemo class accepts two arguments on initialization. 
The first being the name of the file to modify or create.
The second (optional) being an absolute path to the file. 
  If not set, the path will be equivalent to the same diretory where the "data-memo.php" file is located.
``` $datamemo = new datamemo("filename.json", __DIR__ ); ```
