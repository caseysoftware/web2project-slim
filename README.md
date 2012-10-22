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

The primary reason this works is because we've spent quite a bit of time refactoring the backend modules to have consistent interfaces throughout. If you have Add On modules installed, *IF* they follow our naming conventions, they should be compatible with this API wrapper out of the box with no further configuration.

But what are the odds of that? ;)

There are a few layers to this one: the first layer is that you can do an OPTIONS request to the root of the API - http://localhost/web2project-slim/ That will give you a JSON representation of all of the available Resources along with their URIs and a Friendly name. From there you can do an OPTIONS request to any of those Resources and get a JSON representation of the available actions (sample below). From there, any time you attempt any action, you should get back a fully populated Resource with a listing of any errors.

With regards to web2project specifically, the useful bit is that all of the inter-Resource relationships and required/optional fields are auto-discovered by interrogating the objects themselves. Therefore, there's no separate "documentation generation" step.. it's inherently part of the objects themselves, so we get a living API always up to date with the core development.

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