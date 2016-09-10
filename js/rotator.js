		$(document).ready(function(){
		
			// �������� ������ ������ � ������� ��� �������������
			var banners = $("#rotator div").toArray();
			
			// ����� � �����������
			settings = function() {
				this.banners = banners;              // ������ ������ � �������������
				this.sum	 = this.banners.length;  // ���������� ������ � �������������
				this.timeIn  = 1000;                 // ����� ��� ���������
				this.timeOut = 1000;                 // ����� ��� �������
				this.timeView= 4000;                 // ����-��� ��� ������
			}
			
			var obj = new settings();
			if (obj.sum < 1) {
				$("#rotator").html("<p>����������� ��� ������ �� �������!</p>");			
			} 
			else {
				
				// �������� ��� ����������� ����� #rotator
				$("#rotator div").css({
					"display":"none"
				});
				
				// ������� ���� ��� ������ � ����������� �������� ��� ���������� ������
				$("#rotator").prepend("<div id='rotator_view'><img src='/images/load.gif'></div>");
				
				// ������� ������ (����� ������� ����� CSS)
				$("#rotator_view").css({
					"height" : "125px"
				});
				$("#rotator_view img").css({
					"display" : "block",
					"margin" : "22.5px auto",
					"text-align" : "center"
				});
				
				// ��������� ������� ������
				view (0);
			}
			
			function view (num){
				// �������������� ��������� ������ settings()
				var obj = new settings();
				// ���� �������� ��� �����������, ���������� �� �����
				if (num >= obj.sum) num = 0;
				
				var interval = setInterval (function(){
					// ������� ����� ������
					$("#rotator_view *").remove();
					
					// ����� ����������� � ���� ������
					$(obj.banners[num]).clone().prependTo("#rotator_view");
					
					// ����� �����������
					$("#rotator_view div").fadeIn(obj.timeIn);
					
					clearInterval(interval);
					num++;
				},obj.timeIn);
				
				// ������� �����������
				$("#rotator_view div").fadeOut(obj.timeOut);
				
				// ����� ��������� ���� ���� ���� ����������� ������ ������ 
				if (obj.sum > 1) setTimeout(function(){view(num)},obj.timeIn+obj.timeOut+obj.timeView);
			}
		});	
