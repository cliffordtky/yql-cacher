# yql-cacher
A simple PHP class to use YQL cache as temporary key-value storage
Note that this is not persistent storage and it is public. You should not store sensitive data here.

## Methods
There are only 3 methods : put, get and remove.

1) PUT 		-> Simple add a key, a value and a timeout in seconds.

2) GET 		-> Simply provide a key to retrieve the value

3) REMOVE 	-> Simply provide a key to delete the value
	
## Use cases
1) For apps that don't need databases but need to store information temporarily.

2) For apps that require database but want to cache their content for better performance

## Dependencies
1) cURL or file_get_contents

2) YQL caching table (https://developer.yahoo.com/yql/console/#h=desc+yahoo.caching)

## Usage
$cache = new \YQLService\cache();

if ($cache->put('superbird','super bird value','3600')) echo $cache->get('superbird');

To remove an entry, just wait for it to expire or do $cache->remove('thekey');
