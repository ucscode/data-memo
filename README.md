Copyright (c) 2020 by ucscode

# data-memo
Data Memo is a light weight data saver that creates, modifies, retrieve or store data (to any user defined file) as json.

Not every data needs to be stored in database. Some data doesn't even require more than one row of information. Just like session, they may also require instant update at any point in time. It is not always the best option to personally edit those data when you can also do it programically. This is when Data Memo! comes in.

### Inclusion
``` 
  require_once "path/to/data-memo.php"; 
```

### How To Use
DataMemo class accepts two arguments on initialization.

- The first being the name of the file to modify or create. \
- The second (optional) being an absolute path to the file. \
  `If not set, the path will be equivalent to the same diretory where the "data-memo.php" file is located.`
  
``` $datamemo = new datamemo("filename.json", __DIR__ ); ```

If the "filename.json" already contains a valid json file, the $datamemo instance will be filled with the array format of the json data.\
You can then assign new value, get previous or update previous values in the $datamemo instance.

``` 
$datamemo->name = 'uchenna ajah';
$datamemo->developer = 'ucscode';
$datamemo->project = array("name" => "datamemo");
$datamemo->project['website'] = 'https://ucscode.com';
```
Now to get the data in the datamemo object:

```
$datamemo->name; // 'uchenna ajah'
$datamemo->project['name']; // 'datamemo'
$datamemo->project['website']; // 'https://ucscode.com'
```

Now to save the file.
```
$datamemo->save();
```

The above method ```datamemo::save()``` will print the data in $datamemo object as json to ``` __DIR__ . "/filename.json" ```

You can also call the ```$datamemo->pretty_print()``` method before saving to pretty print the json file.

```$datamemo->clear()``` to clear the file

```$datamemo->remove()``` to remove (delete) the file




