# ================================
# FULL WOOCOMMERCE SETUP GUIDE
# FOR: Women's Cloth + Men's Panjabi + Hand Stitch Dress
# USE THIS AS A SINGLE SHELL NOTE / MASTER CHECKLIST
# ================================

# --------------------------------
# PHASE 0: BEFORE STARTING
# --------------------------------
# Goal:
# Build a complete WooCommerce store setup first,
# then create the custom responsive theme later.

# Product types in this store:
# 1. Women's clothing
# 2. Men's panjabi
# 3. Hand stitch dresses

# Main workflow:
# 1. Install WooCommerce
# 2. Complete store settings
# 3. Create core pages
# 4. Create categories
# 5. Create attributes
# 6. Add products
# 7. Configure shipping
# 8. Configure payments
# 9. Configure emails
# 10. Test checkout flow
# 11. Then build custom theme

# --------------------------------
# PHASE 1: INSTALL WOOCOMMERCE
# --------------------------------
# Dashboard > Plugins > Add New
# Search: WooCommerce
# Click: Install Now
# Click: Activate

# After activation:
# WooCommerce setup wizard will appear
# Fill these carefully:
# - Store name
# - Country/Region
# - Address
# - City
# - Postcode
# - Currency
# - Industry
# - Product type

# For your case:
# Store country: Bangladesh
# Currency: BDT / Taka

# --------------------------------
# PHASE 2: GENERAL STORE SETTINGS
# --------------------------------
# Go to:
# Dashboard > WooCommerce > Settings > General

# Set these:

# Store Address:
# - Full address
# - City
# - Country/Region: Bangladesh
# - Postcode

# General Options:
# - Selling location(s): Sell to specific countries
# - Specific country: Bangladesh
# - Shipping location(s): Ship to specific countries only
# - Specific country: Bangladesh
# - Default customer location: Shop base address

# Currency Options:
# - Currency: Bangladeshi Taka (BDT)
# - Currency position: Left or Left with space
# - Thousand separator: ,
# - Decimal separator: .
# - Number of decimals: 2

# Tax:
# - If you want tax system, enable "Enable taxes"
# - If not needed now, leave disabled

# Then click Save Changes

# --------------------------------
# PHASE 3: CHECK WOO PAGES
# --------------------------------
# Go to:
# Dashboard > Pages > All Pages

# Check whether these pages already exist:
# - Shop
# - Cart
# - Checkout
# - My Account

# If missing, create them manually.

# Manual page creation:
# 1. Shop
#    Title: Shop
#    No shortcode needed normally

# 2. Cart
#    Title: Cart
#    Content:
#    [woocommerce_cart]

# 3. Checkout
#    Title: Checkout
#    Content:
#    [woocommerce_checkout]

# 4. My Account
#    Title: My Account
#    Content:
#    [woocommerce_my_account]

# Optional but recommended pages:
# - About Us
# - Contact Us
# - Privacy Policy
# - Terms and Conditions
# - Return & Refund Policy
# - Shipping Policy
# - Track Order
# - FAQ

# --------------------------------
# PHASE 4: PAGE ASSIGNMENT
# --------------------------------
# Go to:
# Dashboard > WooCommerce > Settings > Advanced

# Assign pages properly:
# - Cart page -> Cart
# - Checkout page -> Checkout
# - My account page -> My Account
# - Terms and conditions -> Terms and Conditions page
# - Privacy policy -> Privacy Policy page

# Save Changes

# --------------------------------
# PHASE 5: PRODUCT SETTINGS
# --------------------------------
# Go to:
# Dashboard > WooCommerce > Settings > Products

# Set:
# - Shop page -> Shop
# - Weight unit -> kg or g
# - Dimensions unit -> cm

# Reviews:
# If you want customer reviews:
# - Enable product reviews
# - Enable reviews from verified owners only (recommended)

# Save Changes

# --------------------------------
# PHASE 6: INVENTORY SETTINGS
# --------------------------------
# Go to:
# Dashboard > WooCommerce > Settings > Products > Inventory

# Recommended setup:
# - Enable stock management -> Yes
# - Hold stock (minutes) -> 60
# - Notifications -> Enable if you want low stock emails
# - Low stock threshold -> 2
# - Out of stock threshold -> 0
# - Out of stock visibility -> Hide out of stock items from catalog (optional)
# - Display stock quantity -> optional

# Save Changes

# --------------------------------
# PHASE 7: PRODUCT CATEGORY STRUCTURE
# --------------------------------
# Go to:
# Dashboard > Products > Categories

# Create main categories:

# 1. Women
# slug: women

# 2. Men
# slug: men

# 3. Hand Stitch Collection
# slug: hand-stitch-collection

# Now create subcategories:

# Under Women:
# - Three Piece
#   slug: three-piece
#   parent: Women
#
# - Two Piece
#   slug: two-piece
#   parent: Women
#
# - Kurti
#   slug: kurti
#   parent: Women
#
# - Gown
#   slug: gown
#   parent: Women
#
# - Saree
#   slug: saree
#   parent: Women
#
# - Salwar Kameez
#   slug: salwar-kameez
#   parent: Women
#
# - Unstitched Fabric
#   slug: unstitched-fabric
#   parent: Women

# Under Men:
# - Panjabi
#   slug: panjabi
#   parent: Men
#
# - Punjabi Set
#   slug: punjabi-set
#   parent: Men
#
# - Shirt
#   slug: shirt
#   parent: Men
#
# - Pajama
#   slug: pajama
#   parent: Men
#
# - Waistcoat
#   slug: waistcoat
#   parent: Men

# Under Hand Stitch Collection:
# - Hand Stitch Women Dress
#   slug: hand-stitch-women-dress
#   parent: Hand Stitch Collection
#
# - Hand Stitch Panjabi
#   slug: hand-stitch-panjabi
#   parent: Hand Stitch Collection
#
# - Premium Handmade
#   slug: premium-handmade
#   parent: Hand Stitch Collection
#
# - Custom Stitch
#   slug: custom-stitch
#   parent: Hand Stitch Collection

# Important:
# - Upload category images if needed
# - Keep slugs clean and short
# - Do not create unnecessary duplicate categories

# --------------------------------
# PHASE 8: PRODUCT ATTRIBUTES
# --------------------------------
# Go to:
# Dashboard > Products > Attributes

# Create these attributes:

# 1. Size
# slug: size

# 2. Color
# slug: color

# 3. Fabric
# slug: fabric

# 4. Occasion
# slug: occasion

# 5. Stitch Type
# slug: stitch-type

# 6. Sleeve
# slug: sleeve

# 7. Fit
# slug: fit

# After creating each attribute,
# click "Configure terms"

# Size terms:
# - S
# - M
# - L
# - XL
# - XXL

# Color terms:
# - Black
# - White
# - Red
# - Green
# - Maroon
# - Navy
# - Beige

# Fabric terms:
# - Cotton
# - Silk
# - Linen
# - Georgette
# - Lawn
# - Mixed Fabric

# Occasion terms:
# - Casual
# - Party
# - Wedding
# - Eid
# - Formal

# Stitch Type terms:
# - Hand Stitch
# - Machine Stitch

# Sleeve terms:
# - Full Sleeve
# - Half Sleeve
# - Sleeveless

# Fit terms:
# - Regular
# - Slim
# - Loose

# --------------------------------
# PHASE 9: PRODUCT TAGS
# --------------------------------
# Go to:
# Dashboard > Products > Tags

# Add useful tags:
# - New Arrival
# - Best Seller
# - Premium
# - Eid Collection
# - Exclusive
# - Limited Edition
# - Trending
# - Handmade

# Tags are optional.
# Use them only when they help filtering or labeling.

# --------------------------------
# PHASE 10: SHIPPING SETUP
# --------------------------------
# Go to:
# Dashboard > WooCommerce > Settings > Shipping

# Create shipping zones:

# Zone 1:
# Name: Dhaka
# Region: specific area if possible / or main region logic

# Add shipping method:
# - Flat Rate
# Cost: 80

# Zone 2:
# Name: Rest of Bangladesh
# Region: Bangladesh (excluding Dhaka if your logic allows)
# Add shipping method:
# - Flat Rate
# Cost: 120

# Optional:
# Zone 3:
# Name: Local Pickup
# Method:
# - Local Pickup

# Optional:
# Free Shipping:
# - Minimum order amount: 3000 or your choice

# Shipping classes (optional)
# Go to:
# WooCommerce > Settings > Shipping > Shipping Classes

# Create:
# - standard-dress
# - premium-handmade
# - fragile-packaging

# Use shipping classes only if product-based shipping rules needed

# --------------------------------
# PHASE 11: PAYMENT SETUP
# --------------------------------
# Go to:
# Dashboard > WooCommerce > Settings > Payments

# Recommended starting payment methods:

# 1. Cash on Delivery
# Enable it
# Title: Cash on Delivery
# Description: Pay with cash when your order is delivered.

# 2. Direct Bank Transfer
# Enable it if needed
# Add:
# - Account name
# - Bank name
# - Account number
# - Branch
# - Routing number

# Later add local payment gateways if needed:
# - SSLCommerz
# - bKash supported gateway
# - Nagad supported gateway
# - SurjoPay

# Start simple first:
# COD + Bank Transfer is enough for testing stage

# --------------------------------
# PHASE 12: ACCOUNTS & PRIVACY
# --------------------------------
# Go to:
# Dashboard > WooCommerce > Settings > Accounts & Privacy

# Recommended setup:

# Guest checkout:
# - Allow customers to place orders without an account -> Yes

# Account creation:
# - Allow customers to create an account during checkout -> Yes
# - Allow customers to create an account on the My Account page -> Yes

# Privacy:
# - Assign privacy policy page
# - Configure retention if needed

# Reason:
# Fashion stores usually convert better with guest checkout

# --------------------------------
# PHASE 13: EMAIL SETTINGS
# --------------------------------
# Go to:
# Dashboard > WooCommerce > Settings > Emails

# Check these email types:
# - New order
# - Cancelled order
# - Failed order
# - Processing order
# - Completed order
# - Refunded order
# - Customer invoice
# - Customer note
# - Password reset

# Set sender details:
# - From name: Your Brand Name
# - From address: info@yourdomain.com or hello@yourdomain.com

# Make sure your domain email works properly

# --------------------------------
# PHASE 14: ADD FIRST PRODUCT
# --------------------------------
# Go to:
# Dashboard > Products > Add New

# Product creation workflow:

# A. Product Name
# Example:
# - Premium Maroon Men Panjabi
# - Hand Stitch Floral Dress
# - Elegant Women's Three Piece

# B. Long Description
# Add full details:
# - fabric
# - design
# - fitting
# - use case
# - wash instructions
# - package details

# C. Short Description
# Add short marketing summary:
# Example:
# Premium quality hand stitched dress with elegant design and comfortable fit.

# D. Product Image
# Add one main featured image

# E. Product Gallery
# Add multiple gallery images:
# - front view
# - back view
# - side view
# - close-up fabric
# - detail shot

# F. Categories
# Select:
# - Women / Men / Hand Stitch Collection
# and matching subcategory

# G. Tags
# Example:
# - New Arrival
# - Premium

# --------------------------------
# PHASE 15: SIMPLE PRODUCT SETUP
# --------------------------------
# In Product Data box:
# Select: Simple Product

# Fill:
# - Regular price
# - Sale price (optional)
# - SKU
# - Manage stock? yes/no
# - Stock quantity
# - Stock status
# - Weight
# - Dimensions
# - Shipping class if needed

# Use Simple Product when:
# - only one size
# - only one color
# - no variations
# - fixed product

# --------------------------------
# PHASE 16: VARIABLE PRODUCT SETUP
# --------------------------------
# In Product Data box:
# Select: Variable Product

# Then go to Attributes tab:
# Add existing attribute:
# - Size
# Select values:
# - M
# - L
# - XL
# Check:
# - Visible on product page
# - Used for variations

# Add another attribute:
# - Color
# Select values:
# - Black
# - White
# - Maroon
# Check:
# - Visible on product page
# - Used for variations

# Save attributes

# Then go to Variations tab:
# Select:
# Create variations from all attributes

# Then edit each variation:
# Example:
# - Black / M
# - Black / L
# - White / M
# - White / L
# - Maroon / XL

# For each variation set:
# - image
# - price
# - sale price if needed
# - SKU
# - stock quantity
# - stock status
# - weight / dimensions if needed

# Use Variable Product when:
# - size differs
# - color differs
# - stock differs per variation
# - fashion product has multiple options

# For your business:
# Most products should be variable products

# --------------------------------
# PHASE 17: PRODUCT DATA BEST PRACTICE
# --------------------------------
# Every product should have:
# - Good title
# - Clean slug
# - Main image
# - Gallery images
# - Category
# - Attributes
# - Price
# - Stock status
# - Size data
# - Fabric details
# - Care instruction
# - Short description
# - Full description

# Optional:
# - SKU
# - sale price
# - related tags

# --------------------------------
# PHASE 18: PRODUCT IMAGE RULE
# --------------------------------
# Before uploading many products, decide one fixed image ratio

# Recommended:
# - 4:5 portrait for fashion
# or
# - 1:1 square

# Do not mix random image ratios
# Otherwise archive page will look broken

# Recommended image types:
# 1. Main clean front image
# 2. Back image
# 3. Side image
# 4. Fabric detail image
# 5. Lifestyle image (optional)

# --------------------------------
# PHASE 19: MENU STRUCTURE
# --------------------------------
# Go to:
# Dashboard > Appearance > Menus

# Recommended main menu:
# - Home
# - Women
# - Men
# - Hand Stitch
# - New Arrivals
# - Shop
# - About
# - Contact

# Under Women:
# - Three Piece
# - Two Piece
# - Kurti
# - Gown
# - Saree

# Under Men:
# - Panjabi
# - Shirt
# - Pajama
# - Waistcoat

# Under Hand Stitch:
# - Hand Stitch Women Dress
# - Hand Stitch Panjabi
# - Premium Handmade
# - Custom Stitch

# --------------------------------
# PHASE 20: COUPONS
# --------------------------------
# Go to:
# Dashboard > Marketing > Coupons
# or coupon location depending on Woo version

# Example coupons:
# - EID10
# - FIRSTORDER
# - FREESHIP
# - HANDMADE5

# Add:
# - discount type
# - coupon amount
# - expiry date
# - usage restrictions
# - usage limits

# --------------------------------
# PHASE 21: RECOMMENDED PAGES FOR FULL STORE
# --------------------------------
# Minimum pages:
# - Home
# - Shop
# - Cart
# - Checkout
# - My Account
# - About Us
# - Contact Us
# - Privacy Policy
# - Terms & Conditions
# - Return & Refund Policy
# - Shipping Policy

# Recommended extra pages:
# - New Arrivals
# - Best Sellers
# - Hand Stitch Collection
# - Men Panjabi Collection
# - Women's Collection
# - FAQ
# - Track Order

# --------------------------------
# PHASE 22: TEST ORDER FLOW
# --------------------------------
# Before building theme, test everything

# Create at least:
# - 5 simple products
# - 5 variable products

# Then test:
# 1. Shop page opens
# 2. Category archive opens
# 3. Single product opens
# 4. Variation selection works
# 5. Add to cart works
# 6. Cart update works
# 7. Checkout works
# 8. COD order works
# 9. Order appears in admin
# 10. Customer email arrives
# 11. Admin email arrives
# 12. My Account page works
# 13. Mobile checkout looks okay

# --------------------------------
# PHASE 23: ORDER MANAGEMENT
# --------------------------------
# Go to:
# Dashboard > WooCommerce > Orders

# Learn these statuses:
# - Pending payment
# - Processing
# - On hold
# - Completed
# - Cancelled
# - Refunded
# - Failed

# Suggested use:
# - COD orders after placed -> Processing
# - After delivered -> Completed

# --------------------------------
# PHASE 24: CUSTOMER MANAGEMENT
# --------------------------------
# Customers can:
# - create account
# - view orders
# - update address
# - update account details

# You can monitor basic customer orders from:
# WooCommerce > Orders
# and sometimes:
# WooCommerce > Customers if available

# --------------------------------
# PHASE 25: PERMALINKS
# --------------------------------
# Go to:
# Dashboard > Settings > Permalinks

# Select:
# - Post name

# Keep product URLs clean

# Good examples:
# /product/premium-maroon-panjabi/
# /product-category/men/panjabi/

# --------------------------------
# PHASE 26: WHAT TO DO BEFORE THEME BUILD
# --------------------------------
# Very important:
# Do these first before starting custom theme coding

# 1. Complete WooCommerce general settings
# 2. Create all core pages
# 3. Create category tree
# 4. Create product attributes
# 5. Add at least 10 sample products
# 6. Add variable product examples
# 7. Configure shipping
# 8. Configure COD
# 9. Test checkout
# 10. Finalize image ratio

# Reason:
# Without real WooCommerce data,
# custom theme design will be blind and incomplete

# --------------------------------
# PHASE 27: YOUR STORE'S RECOMMENDED FINAL STRUCTURE
# --------------------------------
# Main categories:
# - Women
# - Men
# - Hand Stitch Collection

# Main attributes:
# - Size
# - Color
# - Fabric
# - Occasion
# - Stitch Type
# - Sleeve
# - Fit

# Main pages:
# - Home
# - Shop
# - Women
# - Men
# - Hand Stitch
# - Cart
# - Checkout
# - My Account
# - About
# - Contact
# - Privacy Policy
# - Terms
# - Return Policy
# - Shipping Policy

# Payment:
# - COD
# - Bank Transfer
# - local gateway later

# Shipping:
# - Dhaka
# - Rest of Bangladesh
# - optional free shipping threshold

# --------------------------------
# PHASE 28: LAUNCH CHECKLIST
# --------------------------------
# Final checklist before launch:

# [ ] WooCommerce installed
# [ ] Store address set
# [ ] Currency set to BDT
# [ ] Shop page assigned
# [ ] Cart page assigned
# [ ] Checkout page assigned
# [ ] My Account page assigned
# [ ] Terms page assigned
# [ ] Categories created
# [ ] Attributes created
# [ ] Tags created if needed
# [ ] Shipping zones created
# [ ] COD enabled
# [ ] Bank transfer enabled if needed
# [ ] Emails configured
# [ ] 10+ products added
# [ ] Variable products tested
# [ ] Checkout tested
# [ ] Order emails tested
# [ ] Mobile tested
# [ ] Product image ratio consistent

# --------------------------------
# PHASE 29: AFTER THIS
# --------------------------------
# After WooCommerce setup is complete,
# then start custom coded theme creation.

# Your custom homepage design plan:
# - White premium hero section
# - Black premium category/product grid section
# - Desktop/tablet = design-like grid
# - Mobile = slider
# - Latest products dynamically from WooCommerce
# - No Elementor
# - Fully custom coded responsive theme

# Best workflow from here:
# 1. Finish WooCommerce setup fully
# 2. Add sample products and product images
# 3. Then generate the custom theme with Codex prompt

# ================================
# END OF FULL MASTER SHELL
# ================================