web2project-slim
================

This is an attempt to make a proper API using Slim as a foundation.


== TODO ==

-  Provide a variety of output formats/media
-  Make use of accept headers to determine output format
-  Support authentication


== DONE ==

-  Figure out how to identify and include the super/sub-resources automatically
-  Catch undefined resources (aka ones that don't have classes to map to) before the E_Fatal and return a 400 or 404
-  Better error handling all the way around


== OPTIONS ==

One of the places that this differs from a lot of REST-style APIs is the implementation of the OPTIONS verb. I have wired it to give information on the chosen resource. More importantly, behind the scenes, it's actually introspecting the object itself to determine which fields are required, optional, etc. Therefore, regardless of whether someone maintains this specific API, it should stay functional with web2project for quite a while.

The primary reason this works is because we've spent quite a bit fo time refactoring the backend modules to have consistent interfaces throughout. If you have Add On modules installed, *IF* they follow our naming conventions, they should be compatible with this API wrapper out of the box with no further configuration.

But what are the odds of that? ;)


Results of: curl -X OPTIONS http://localhost/web2project-slim/links

{
    "actions": {
        "create": {
            "href": "/web2project-slim/links", 
            "method": "POST", 
            "optional": [ "link_project", "link_url", "link_task", "link_name", "link_parent", "link_description", "link_owner", "link_date", "link_icon", "link_category" ],
            "required": [ "link_name", "link_url", "link_owner" ]
        }, 
        "delete": {
            "href": "/web2project-slim/links:id", 
            "method": "DELETE", 
            "required": [ "link_id" ]
        }, 
        "edit": {
            "href": "/web2project-slim/links:id", 
            "method": "PATCH", 
            "optional": [ "link_project", "link_url", "link_task", "link_name", "link_parent", "link_description", "link_owner", "link_date", "link_icon", "link_category" ], 
            "required": [
                "link_id"
            ]
        }, 
        "filter": {
            "href": "/web2project-slim/links", 
            "method": "GET", 
            "optional": ["page", "limit"]
        }, 
        "help": {
            "href": "/web2project-slim/links", 
            "method": "OPTIONS"
        }, 
        "index": {
            "href": "/web2project-slim/links", 
            "method": "GET"
        }, 
        "search": {
            "href": "/web2project-slim/links", 
            "method": "GET", 
            "required": [ "search" ]
        }, 
        "view": {
            "href": "/web2project-slim/links:id", 
            "method": "GET", 
            "required": [ "link_id" ]
        }
    }, 
    "resource_uri": "/links", 
    "root_uri": "/web2project-slim", 
    "self": "/web2project-slim/links"
}