# Virtual File System CLI

This project is a Symfony-based CLI application that emulates a virtual file system. Users can perform various file and folder operations, including reading directories, adding or removing files, creating or deleting folders, and viewing folder structures.

## Requirements

- PHP 8.x
- Symfony Console Component
- JSON File for Data Persistence

## Installation

1. Clone the repository.
2. Install dependencies using Composer:
   ```bash
   composer install
   ```

## Usage

php bin/console vfm:execute action path [name]

action: The action to perform. Available options include:

read-folder: Reads the contents of a specified directory.  
read-tree: Reads the entire folder tree structure.  
add-file: Adds a file to a specified directory.  
remove-file: Removes a file from a specified directory.  
create-folder: Creates a new folder in a specified path.  
delete-folder: Deletes an existing folder.  

path: The path to the directory where the action should be applied.

[name]: (Optional) The name of the file or folder to add, remove, or create.

File that is read and written is located `'storage/virtual_file_storage.json'`  
Used data for project can be located `'storage/example_data.json'`

### Examples

```bash
php bin/console vfm:execute read-folder /downloads/
```
```bash
php bin/console vfm:execute add-file /downloads/movies file.txt
```



