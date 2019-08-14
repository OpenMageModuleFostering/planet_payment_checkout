    var wpwlOptions = {
        useSummaryPage: true,
		paymentTarget: 'my_iframe',
		shopperResultTarget: 'my_iframe',
        onSaveTransactionData: function(data){
            //transaction data were sent and saved
            payment.save();
        }
    }
    
