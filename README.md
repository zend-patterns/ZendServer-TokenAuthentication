Token Authentication module for use in ZendServer 6.1
-----------------------------------------------------

Installation:
Extract module content into <install_dir>/gui/vendor/TokenAuthentication
Run appropriate SQL file found in utils/sqls (sqlite or mysql, as needed)
Add 'TokenAuthentication' to modules list in <install_dir>/gui/config/application.config.php

Note: This installation process or parts of it may have to be executed following upgrades or updates to the application.

Usage:
Use a signed webapi request to <ZendServer>:10081/ZendServer/Api/generateToken to generate a token.
Expected xml output (json output available too):

    <zendServerAPIResponse xmlns="http://www.zend.com/server/api/1.3">
      <requestData>
        <apiKeyName><![CDATA[...]]></apiKeyName>
        <method>tokenGenerate</method>
      </requestData>
      <responseData>
        <authenticationToken>
        <token>1c6beff1468ee20fce4c3d76774ff93d200977fac70a46ca33661c45fe2e2e04</token>
        <expires>2013-03-18T09:02:45+02:00</expires>
        <expiresTimestamp>1363590165</expires>
        </authenticationToken>
      </responseData>
    </zendServerAPIResponse>
    
Retrieve the token hash and browse to:

    <ZendServer>:10081/ZendServer/Token?hash=<token hash>

In our example:

    <ZendServer>:10081/ZendServer/Token?hash=1c6beff1468ee20fce4c3d76774ff93d200977fac70a46ca33661c45fe2e2e04

This action should login you directly into ZendServer as the user bound to the apiKey used to generate the token.
Note that each token has a 30 seconds expiration timeout.

Errors about the operation are written out in the zend server ui log.
