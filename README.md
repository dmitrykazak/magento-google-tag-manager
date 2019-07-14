# Magento 2 GTM (Google Tag Manager)
GTM Extension for Magento 2. GTM allows you to quickly and easily update tracking codes and related code fragments to help manage the lifecycle of e-marketing tags

# Installation
#### Step-by-step to install the Magento 2 extension through Composer:
1. Locate your Magento 2 project root.

2. Install the Magento 2 extension using **composer**  
````composer require dmitrykazak/magento-google-tag-manager ````

3. After installation is completed the extension:
 ```bash
# Enable the extension and clear static view files
 $ bin/magento module:enable DK_GoogleTagManager --clear-static-content
 
 # Update the database schema and data
 $ bin/magento setup:upgrade
 
 # Recompile your Magento project
 $ bin/magento setup:di:compile
 
 # Clean the cache 
 $ bin/magento cache:flush
```

# Magento Google Tag Manager Features

TBD.

#### Links
* [Contact with me](https://developer-vub3295.slack.com/messages/CLG5P5A0N)