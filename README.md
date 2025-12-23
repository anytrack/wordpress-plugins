# AnyTrack.io Wordpress Plugins

- [AnyTrack Affiliate Link Manager](./anytrack-affiliate-link-manager/trunk) - Generate safe and
nicer offer links with built-in support for the AnyTrack [Affiliate Tracking Software](https://anytrack.io/).
- [AnyTrack for WooCommerce](./anytrack-for-woocommerce/trunk) - Send your conversions to Google Ads, 
[Facebook Conversion API](https://anytrack.io/facebook-conversion-api), Taboola, Outbrain, and Bing to 
improve your Ads ROAS, and build meaningful audiences.

### Local development:

There are multiple ways to run the plugin with local changes:
1. After your changes zip the plugin and install on "Network admin" level in our wordpress instance. (You cannot install plugin to site level as we have multisite administrated wordpress instance).
2. Through docker (you can also create some compose file) (not done yet)
   - The idea is to have local wordpress instance, setup as woocommerce by installing and configuring required plugins including stripe or some method that supports easy test payments.
   - Then connect a volume: publuc/wp-content/plugins/anytrack-for-woocommerce to here the project ./anytrack-for-woocommerce/trunk
3. Connect to our kinsta server either preview (stage) or production
    - The idea is to make easy sync with the kinsta server. Create an ssh connection, setup some deployment to simplify sync (according to your IDE, e.g. create a jetbrains deployment and make sure having correct mapping)
    - Then trigger sync on file level manually once having a change in the file.

Note: There are more ways, so if you have your preferred way just go ahead and add it here.\n
Note2: All methods are impacting immediatly once the file in server is changed. No caching technique is used in our server.\n
Note3: You can first connect and play with kinsta staging environment. But it's fine to also use our production one as it's still for demo/testing purposes but not a real shop. The main difference is prod calls our prod and staging calls our preview.

### Deployment:

#### Prequisites:
- svn access

#### Steps:
- Shift the plugin version and write the change log in the readme.txt in the corresponding folder.
- Run the release script

...TBU
