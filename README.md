ScpperDB
=======================

This is a project to provide a web interface for a database containing data extracted from SCP Foundation wiki (http://www.scp-wiki.net/) and its branches.
Database itself is updated with the help of SCP web crawler.
Source code for website and web-crawler is available at https://github.com/FiftyNine?tab=repositories

Author: Alexander "FiftyNine" Krivopalov, E-mail: lixbart@mail.ru

## API

**Endpoint**: /api/[method]

**Method**: page  
**Description**: retrieves a page by id  
**Type**: GET  
**Arguments**:  
+ "**id**": Wikidot id of the page to retrieve.

**Returns**:  
JSON object containing a page metadata.

<details>
  <summary><b>Example</b></summary>
<p>
  
**Request**:
```
/api/page?id=13327521
```
Selects a page with WikidotId = 13327521 (SCP-1981 - "RONALD REAGAN CUT UP WHILE TALKING")

**Response**:
```json
{  
   "id":13327521,
   "name":"scp-1981",
   "title":"SCP-1981",
   "altTitle":"\u0022RONALD REAGAN CUT UP WHILE TALKING\u0022",
   "status":"Original",
   "kind":"SCP",
   "creationDate":{  
      "date":"2012-05-14 05:44:15.000000",
      "timezone_type":3,
      "timezone":"America\/New_York"
   },
   "rating":1120,
   "cleanRating":1117,
   "contributorRating":221,
   "adjustedRating":767,
   "wilsonScore":0.96292048692703,
   "rank":6,
   "authors":[  
      {  
         "id":389284,
         "user":"Digiwizzard",
         "role":"Author"
      }
   ]
}
```
</p>
</details>

---

**Method**: user  
**Description**: retrieves a user by id  
**Type**: GET  
**Arguments**:  
+ "**id**": Wikidot id of the user to retrieve.

**Returns**:  
JSON object containing user metadata.

<details>
  <summary><b>Example</b></summary>
<p>
  
**Request**:
```
/api/user?id=224440
```
Selects a user with WikidotId = 224440 (TheDuckman)

**Response**:
```json
{  
   "id":224440,
   "name":"theduckman",
   "displayName":"TheDuckman",
   "deleted":0,
   "activity":{  
      "en":{  
         "votes":2613,
         "revisions":1049,
         "pages":67,
         "lastActive":{  
            "date":"2017-02-07 13:10:45.000000",
            "timezone_type":3,
            "timezone":"America\/New_York"
         },
         "member":{  
            "date":"2008-10-19 17:17:07.000000",
            "timezone_type":3,
            "timezone":"America\/New_York"
         },
         "highestRating":922,
         "totalRating":"8442"
      },
      "fr":{  
         "votes":1,
         "revisions":0,
         "pages":0,
         "lastActive":{  
            "date":"2015-05-23 00:00:00.000000",
            "timezone_type":3,
            "timezone":"America\/New_York"
         },
         "member":{  
            "date":"2013-01-25 11:57:36.000000",
            "timezone_type":3,
            "timezone":"America\/New_York"
         },
         "highestRating":null,
         "totalRating":null
      },
      "ru":{  
         "votes":0,
         "revisions":0,
         "pages":0,
         "lastActive":null,
         "member":{  
            "date":"2011-09-15 02:47:19.000000",
            "timezone_type":3,
            "timezone":"America\/New_York"
         },
         "highestRating":null,
         "totalRating":null
      }
   }
}
```
</p>
</details>

---

**Method**: find-pages  
**Description**: retrieves up to *limit* pages from the specified wiki with part of the name matching *title* 
**Type**: GET  
**Arguments**:  
+ "**site**": Short name from the list of available sites (see below)  
+ "**title**": Search query, part of page's name (i.e. "173" will match "SCP-173", "SCP-1173", etc). Between 3 and 256 characters. 
+ "**limit**": Maximum number of rows returned by the query. Limited to 50.  
+ "**random**": Bit flag indicating whether resulting list of pages should be randomized.  
&nbsp;&nbsp;&nbsp;&nbsp;"0" - returns *limit* pages ordered by clean rating, descending (default)  
&nbsp;&nbsp;&nbsp;&nbsp;"1" - returns random selection of *limit* pages from the original query.  

**Returns**:  
JSON object containing only one field "pages", which is an array of all found pages.

<details>
  <summary><b>Example</b></summary>
<p>
  
**Request**:
```
/api/find-pages?site=en&title=king&limit=2&random=1
```
Randomly selects from the english wiki up to 2 pages which have "king" in their title or alternative title.

**Response**:
```json
{  
   "pages":[  
      {  
         "id":13327521,
         "name":"scp-1981",
         "title":"SCP-1981",
         "altTitle":"\u0022RONALD REAGAN CUT UP WHILE TALKING\u0022",
         "status":"Original",
         "kind":"SCP",
         "creationDate":{  
            "date":"2012-05-14 05:44:15.000000",
            "timezone_type":3,
            "timezone":"America\/New_York"
         },
         "rating":1120,
         "cleanRating":1117,
         "contributorRating":221,
         "adjustedRating":767,
         "wilsonScore":0.96292048692703,
         "rank":6,
         "authors":[  
            {  
               "id":389284,
               "user":"Digiwizzard",
               "role":"Author"
            }
         ]
      },
      {  
         "id":3222822,
         "name":"scp-701",
         "title":"SCP-701",
         "altTitle":"The Hanged King\u0027s Tragedy",
         "status":"Original",
         "kind":"SCP",
         "creationDate":{  
            "date":"2009-03-27 08:25:19.000000",
            "timezone_type":3,
            "timezone":"America\/New_York"
         },
         "rating":686,
         "cleanRating":682,
         "contributorRating":162,
         "adjustedRating":523,
         "wilsonScore":0.96553260087967,
         "rank":29,
         "authors":[  
            {  
               "id":298351,
               "user":"tinwatchman",
               "role":"Author"
            }
         ]
      }
   ]
}
```
</p>
</details>

---

**Method**: find-users  
**Description**: retrieves up to *limit* users from the with part of the name matching *name* 
**Type**: GET  
**Arguments**:  
+ "**site**": Optional. Short name from the list of available sites (see below). If specified, only searches amongst members of the site. Otherwise - amongst all users.
+ "**name**": Search query, part of user's name (i.e. "cle" will match "Dr Clef", "Agent MacLeod", etc). Between 3 and 256 characters. 
+ "**limit**": Maximum number of rows returned by the query. Limited to 50.  

**Returns**:  
JSON object containing only one field "users", which is an array of all found pages.

<details>
  <summary><b>Example</b></summary>
<p>
  
**Request**:
```
/api/find-users?site=en&name=gene&limit=1
```
Selects a member of the english wiki who has "gene" in their name

**Response**:
```json
{  
   "users":[  
      {  
         "id":634139,
         "name":"gene-r",
         "displayName":"Gene R",
         "deleted":0,
         "activity":{  
            "en":{  
               "votes":245,
               "revisions":212,
               "pages":13,
               "lastActive":{  
                  "date":"2017-02-07 13:10:20.000000",
                  "timezone_type":3,
                  "timezone":"America\/New_York"
               },
               "member":{  
                  "date":"2011-05-06 22:54:36.000000",
                  "timezone_type":3,
                  "timezone":"America\/New_York"
               },
               "highestRating":168,
               "totalRating":"866"
            },
            "ru":{  
               "votes":768,
               "revisions":7673,
               "pages":636,
               "lastActive":{  
                  "date":"2016-09-21 19:51:58.000000",
                  "timezone_type":3,
                  "timezone":"America\/New_York"
               },
               "member":{  
                  "date":"2010-11-29 00:43:58.000000",
                  "timezone_type":3,
                  "timezone":"America\/New_York"
               },
               "highestRating":155,
               "totalRating":"10666"
            }
         }
      }
   ]
}
```
</p>
</details>

---

**Method**: tags  
**Description**: retrieves up to *limit* pages from the specified wiki, selected using provided tags  
**Type**: GET  
**Arguments**:  
+ "**site**": Short name from the list of available sites (see below)  
+ "**method**": How to combine provided tags for the query ("and"/"or")     
&nbsp;&nbsp;&nbsp;&nbsp;"and" - only pages that have all the tags (default)  
&nbsp;&nbsp;&nbsp;&nbsp;"or" - pages that contain any of the tags.  
+ "**tags**": List of tags, each prefixed with "+" or "-", separated by commas  
&nbsp;&nbsp;&nbsp;&nbsp;"+" indicates that pages containing this tag must be included in the query  
&nbsp;&nbsp;&nbsp;&nbsp;"-" indicates that pages containing this tag must be excluded from the query  
&nbsp;&nbsp;&nbsp;&nbsp;Each tag MUST be prefixed by only ONE of those options.  
+ "**limit**": Maximum number of rows returned by the query. Limited to 50.  
+ "**random**": Bit flag indicating whether resulting list of pages should be randomized.  
&nbsp;&nbsp;&nbsp;&nbsp;"0" - returns *limit* pages ordered by clean rating, descending (default)  
&nbsp;&nbsp;&nbsp;&nbsp;"1" - returns random selection of *limit* pages from the original query.  

**Returns**:  
JSON object containing only one field "pages", which is an array of all selected pages.

<details>
  <summary><b>Example</b></summary>
<p>
  
**Request**:
```
/api/tags?site=en&method=and&tags=%2Bscp%2C%2Bmemetic%2C-joke&limit=2&random=1
```
Randomly selects from the english wiki up to 2 pages that have both tags "scp" and "memetic", but DO NOT have a "joke" tag

**Response**:
```json
{  
   "pages":[  
      {  
         "name":"scp-701",
         "title":"SCP-701",
         "altTitle":"The Hanged King\u0027s Tragedy",
         "status":"Original",
         "kind":"SCP",
         "creationDate":{  
            "date":"2009-03-27 08:25:19.000000",
            "timezone_type":3,
            "timezone":"America\/New_York"
         },
         "rating":819,
         "cleanRating":813,
         "contributorRating":181,
         "adjustedRating":349,
         "wilsonScore":0.96673589944839,
         "rank":29,
         "authors":[  
            {  
               "user":"tinwatchman",
               "role":"Author"
            }
         ]
      },
      {  
         "name":"scp-1893",
         "title":"SCP-1893",
         "altTitle":"The Minotaur\u0027s Tale",
         "status":"Original",
         "kind":"SCP",
         "creationDate":{  
            "date":"2012-03-17 22:35:44.000000",
            "timezone_type":3,
            "timezone":"America\/New_York"
         },
         "rating":736,
         "cleanRating":732,
         "contributorRating":165,
         "adjustedRating":286,
         "wilsonScore":0.96474659442902,
         "rank":39,
         "authors":[  
            {  
               "user":"Eskobar",
               "role":"Author"
            }
         ]
      }
   ]
}
```
</p>
</details>  

---

##### List of available sites (API names in quotes):  
+ SCP Foundation (scp-wiki.net):                  "en"  
+ Russian branch (scpfoundation.ru):              "ru"  
+ Korean branch (ko.scp-wiki.net):                "ko"  
+ Japanese branch (ja.scp-wiki.net):              "ja"  
+ French branch (fondationscp.wikidot.com):       "fr"  
+ Spanish branch (lafundacionscp.wikidot.com):    "es"  
+ Thai branch (scp-th.wikidot.com):               "th"  
+ Polish branch (scp-wiki.net.pl):                "pl"  
+ German branch (scp-wiki-de.wikidot.com):        "de"  
+ Chinese branch (scp-wiki-cn.wikidot.com):       "cn"  
+ Italian branch (fondazionescp.wikidot.com):     "it"  
