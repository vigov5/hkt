LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
INSERT INTO `faqs` VALUES
  (1,0,1,'What is Framgia Hyakkaten','The Hyakkaten (百貨店) in Japanese means \"department store\" (Cửa hàng bách hoá).<br>\r\n        And the purpose of this service is to provide Framgia\'s Members a store where they can buy or sell what they want.<br>\r\n        Framgia Hyakkaten is powered by the PhalconPHP Framework.',1000,'2014-05-08 21:36:45',NULL),
  (2,0,1,'Why couldn\'t I access to any pages after logging in ?','Because Framgia Hyakkaten is a restricted service, your account needs to be authorized.<br>\r\n        So please use an easy-to-realize username and email when register.<br>\r\n        After that please contact the administrators  to authorize it for you.',2000,'2014-05-08 21:37:11',NULL),
  (3,0,1,'How can I deposit money to my account in Hyakkaten','Go to the Deposit page, just buy it like other items.<br>\r\nThen an invoice will be created. <br> \r\nGive money to the administrator, ask him to accept the invoice then your wallet will be increased !<br>\r\nAt the moment, there is no way to deposit accept for give money directly to administrator, but the \"Internet Banking Transfer\" feature is being considered and may be available in the feature !',3000,'2014-05-08 21:41:40','2014-05-08 21:57:35'),
  (4,0,1,'How can I withdraw money to my account in Hyakkaten','Go to Withdraw page, choose how many money you want to withdraw. <br>\r\nCreate an invoice, then contact the administrator to receive the money !',4000,'2014-05-08 21:43:39',NULL),
  (5,0,1,'Beside the main wallet, I also have some HCoin, what does it mean ?','The HCoin (HKT Coin) is the Framgia Hyakkaten\'s virtual money. <br>\r\nYou can use it to buy goods in Framgia Hyakkaten, but you can not change it to real money (VND). <br>\r\nIt can not be withdrawn.',5000,'2014-05-08 21:44:34',NULL),
  (6,0,1,'How can I gain HCoin ?','You can receive HCoin when you buy goods in Hyakkaten. In general, you will receive 1 HCoin after purchasing 100 VND.<br>\r\nDuring special campaigns, you can receive more than that rate.',6000,'2014-05-08 21:45:29',NULL),
  (7,0,1,'Why didn\'t I receive any HCoin after purchasing an item ?','After purchasing an item, an invoices will be created, and your wallet will be deducted. But it does not mean the transaction had been completed. You can still <span class=\"text-danger\">CANCEL</span> the invoice to get your money back.<br>\r\n        Or because of some reason, your invoice can be <span class=\"text-warning\">REJECTED</span> by the seller, so at that time you will also get the refund.<br>\r\n        Only when the invoice is <span class=\"text-success\">ACCEPTED</span> by the seller, the transaction is considered as <span class=\"text-primary\">COMPLETED</span>, and you can not get the refund any more. But at that time, you will receive HCoin.',7000,'2014-05-08 21:46:00',NULL),
  (8,0,1,'I want to sell a good, what do I need to do ?','At first, you should check the currently available items in the item page. If the item you want to sell has already existed, you can send request to become the seller of that item.<br>\r\nAfter the administrator accepts your request, you can sell it to other members.',8000,'2014-05-08 21:46:32',NULL),
  (9,0,1,'I have created an item but why doesn\'t it appear in my selling items list ?','After you create an item, by default it will be unavailable which means that it will not appear in your selling items page, and it also be unavailable for other members to access.<br>\r\nYour item need to be checked by the administrator. If it is OK, it will become available, and then you can start selling it.<br>\r\nSo if you have waited too long, fell free to contact the administrators :D',9000,'2014-05-08 21:47:02',NULL),
  (10,0,1,'Can I send money to other accounts ?','At that time, you can only send HCoin to others.<br>\r\nSending real money to other accounts will be available in the feature after we find an easy and secure way for users do it.',10000,'2014-05-08 21:47:22','2014-05-08 21:53:25'),
  (11,0,1,'I want to develop other services for Framgia Member, which sometime requires payment or something else like this. Do you provide API for me to make payment through Hyakkaten.','\"Paying with HKT\" is one of the interesting features that I really want to deploy :D<br>\r\nBut it seems to be very hard for me to do it alone so that feature will not available in the near feature.',11000,'2014-05-08 21:47:51',NULL),
  (12,0,1,'I want to learn PHP. I want to learn Phalcon Framework. I want to help you to deploy more features to Framgia Hyakkaten. What should I do ?','Please fell free to contact us. <br>\r\nFind more information at <a href=\"/contact\">contact page</a><br>You are always welcome (dance)',12000,'2014-05-08 21:49:54',NULL);
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;

INSERT INTO `setting` VALUES (0,1,3,1,NULL,NULL);