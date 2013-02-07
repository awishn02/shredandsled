var NewsTable = {
    loader: "/img/snowman.gif",
    total_page: 0,
    posts_per_page: 2,
    data: null,
    currentIndex:0,
    init: function(data){
        this.data = jQuery.parseJSON(data);
        this.posts_per_page = 2;
	this.total_pages = Math.ceil(this.data.length / this.posts_per_page);
        this.show(1)
    },
    show: function(page){
        if(page == 1){
            this.currentIndex = 0;
            this.endIndex = 1;
        }else{
	    this.currentIndex = ((page - 1) * this.posts_per_page);
            this.endIndex = this.currentIndex + (this.posts_per_page - 1);
        }
		        
        var jsonObj = this.data[this.currentIndex];
        if(!jsonObj) {
            //alert("No more data");
            return;
        }
        
        var posts = []
        
	for (var i=this.currentIndex; i <= this.endIndex; i++)
        {	
            var post = this.data[i];
            if (post) {
                posts.push(post);
            } else {
                break;
            }
        }
	if (posts) {
            $("#news-table").html("<div class='loading_block'><img src='" + this.loader + "' /></div>");
            $.ajax({
                url: "home/news-table",
                type: "POST",
                data: {"news":posts, "page": page, "total_pages":this.total_pages},
                success: function(htmlresponse){
                    $('#news-table').html(htmlresponse);
                }
            });
        }
    },
    nextHandler: function() {
        ForumTable.show(ForumTable.currentIndex + 1);
    },
    previousHandler: function() {
        ForumTable.show(ForumTable.currentIndex - 1);
    }
}