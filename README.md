# Magento 2 GTM (Google Tag Manager)
GTM Extension for Magento 2. GTM allows you to quickly and easily update tracking codes and related code fragments to help manage the lifecycle of e-marketing tags

# Installation
#### Step-by-step to install the Magento 2 extension through Composer:
1. Locate your Magento 2 project root.

2. Install the Magento 2 extension using **composer**  
 ```bash 
 composer require dmitrykazak/magento-google-tag-manager 
 ```

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
#### Manually (not recommended)
* Download the extension of the required version
* Unzip the file
* Create a folder ````{root}/app/code/DK/GoogleTagManager````
* Copy the files this folder

# Magento Google Tag Manager Features

* Measuring Product Impressions
* Measuring Product Clicks
* Measuring Views of Product Details
* Measuring Additions or Removals from a Shopping Cart
* Measuring a Checkout
* Measuring Checkout Options
* Measuring Purchases

> [More Details](https://developers.google.com/tag-manager/enhanced-ecommerce?hl=en)

#### General Configuration

* Login to Magento Admin, ````Configuration > Sales > Google API > Google Tag Manager.````
* Enter your GTM account number

![Settings](https://user-images.githubusercontent.com/5670207/61357887-d045e580-a881-11e9-916a-d9d8a012bfb7.png)
* Setup tags, triggers and variable in [Google Tag Manager](https://tagmanager.google.com)

#### Data Layers

TBD.

#### Links
* [Contact with me](https://developer-vub3295.slack.com/messages/CLG5P5A0N)