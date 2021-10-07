# BackendTest
Delocal Zrt. - PHP Fejlesztői teszt - megoldás

## Contacts API

**The task:**
The API represents a contacts entity and reachable through http. The contacts should be
stored in a database and the input/output of the API should be in ​ **JSON** ​ format:

- return all contacts: [http://example.com/contacts](http://example.com/contacts)
- return a contact by id: [http://example.com/contacts/{id}](http://example.com/contacts/{id})
- modify a contact (change email at an existing contact...)
- create a contact (all fields are required)

Reading should be handled with ​ **GET** ​ and writing with ​ **PUT** ​.
Data structure of a contact:
- name
- email
- phone number
- address

**Additional info:**
* You should use Php.
* You should not use any framework.
* You should write production code that you would push to a repository and solves the feature.
* You should submit the solution even if it is not fully finished.

**Developer info:**
* SQL Dump is located in the sql folder.
* Set the database connection information in config.php.
* The frontend section is for testing purposes only.