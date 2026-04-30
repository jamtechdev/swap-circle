$(".image-box").click(function(event) {
	var previewImg = $(this).children("img");

	$(this)
		.siblings()
		.children("input")
		.trigger("click");

	$(this)
		.siblings()
		.children("input")
		.change(function() {
			var reader = new FileReader();

			reader.onload = function(e) {
				var urll = e.target.result;
				console.log(urll);
				$(previewImg).attr("src", urll);
				$(previewImg).attr("class", "image");
				previewImg.parent().css("background", "transparent");
				previewImg.show();
				previewImg.siblings("p").hide();
			};
			reader.readAsDataURL(this.files[0]);
		});
});


// 

// const imageBoxes = document.querySelectorAll('.image-box');

// function handleInputChange(event) {
//   const input = event.currentTarget;
//   const previewImg = input.closest('.image-box').querySelector('img');
//   const file = input.files[0];
//   const reader = new FileReader();

//   reader.onload = function(e) {
//     const url = e.target.result;
//     previewImg.src = url;
//     previewImg.className = 'image';
//     previewImg.parent().style.background = 'transparent';
//     previewImg.style.display = 'block';
//     previewImg.nextElementSibling.style.display = 'none';
//   };

//   reader.readAsDataURL(file);
// }

// function handleImageBoxClick(event) {
//   const imageBox = event.currentTarget;
//   const input = imageBox.querySelector('input[type=file]');
//   input.click();
// }

// imageBoxes.forEach(imageBox => {
//   imageBox.addEventListener('click', handleImageBoxClick);
//   imageBox.querySelector('input[type=file]').addEventListener('change', handleInputChange);
// });


// 


// const imageBoxes = document.querySelectorAll('.image-box');

// imageBoxes.forEach(function(imageBox) {
//   imageBox.addEventListener('click', function(event) {
//     const previewImg = this.querySelector('img');
//     const input = this.querySelector('input[type=file]');
    
//     input.dispatchEvent(new MouseEvent('click'));

//     input.addEventListener('change', function() {
//       const reader = new FileReader();
      
//       reader.onload = function(e) {
//         const urll = e.target.result;
//         previewImg.setAttribute('src', urll);
//         previewImg.setAttribute('class', 'image');
//         previewImg.parentElement.style.background = 'transparent';
//         previewImg.style.display = 'block';
//         previewImg.nextElementSibling.style.display = 'none';
//       };
      
//       reader.readAsDataURL(input.files[0]);
//     });
//   });
// });




