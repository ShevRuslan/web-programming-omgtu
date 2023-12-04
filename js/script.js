var x, i, j, l, ll, selElmnt, a, b, c;
x = document.getElementsByClassName("custom-select");
l = x.length;
for (i = 0; i < l; i++) {
  selElmnt = x[i].getElementsByTagName("select")[0];
  ll = selElmnt.length;
  a = document.createElement("DIV");
  a.setAttribute("class", "select-selected");
  a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
  x[i].appendChild(a);
  b = document.createElement("DIV");
  b.setAttribute("class", "select-items select-hide");
  for (j = 1; j < ll; j++) {
    c = document.createElement("DIV");
    c.innerHTML = selElmnt.options[j].innerHTML;
    c.addEventListener("click", function (e) {
      var y, i, k, s, h, sl, yl;
      s = this.parentNode.parentNode.getElementsByTagName("select")[0];
      sl = s.length;
      h = this.parentNode.previousSibling;

      for (i = 0; i < sl; i++) {
        if (s.options[i].innerHTML == this.innerHTML) {
          s.selectedIndex = i;
          h.innerHTML = this.innerHTML;
          y = this.parentNode.getElementsByClassName("same-as-selected");
          yl = y.length;
          for (k = 0; k < yl; k++) {
            y[k].removeAttribute("class");
          }
          this.setAttribute("class", "same-as-selected");
          break;
        }
      }
      h.click();
    });
    b.appendChild(c);
  }
  x[i].appendChild(b);
  a.addEventListener("click", function (e) {
    e.stopPropagation();
    closeAllSelect(this);
    this.nextSibling.classList.toggle("select-hide");
    this.classList.toggle("select-arrow-active");
  });
}

function closeAllSelect(elmnt) {
  var x,
    y,
    i,
    xl,
    yl,
    arrNo = [];
  x = document.getElementsByClassName("select-items");
  y = document.getElementsByClassName("select-selected");
  xl = x.length;
  yl = y.length;
  for (i = 0; i < yl; i++) {
    if (elmnt == y[i]) {
      arrNo.push(i);
    } else {
      y[i].classList.remove("select-arrow-active");
    }
  }
  for (i = 0; i < xl; i++) {
    if (arrNo.indexOf(i)) {
      x[i].classList.add("select-hide");
    }
  }
}

document.addEventListener("click", closeAllSelect);

// Получение ссылки на элемент select-selected
var selectSelected = document.querySelector(".select-selected");

// Создание экземпляра MutationObserver
var observer = new MutationObserver(function (mutations) {
  mutations.forEach(function (mutation) {
    if (mutation.type === "childList") {
      // Обработка изменений внутри элемента select-selected
      console.log("Содержимое элемента: " + selectSelected.innerHTML);

      document.querySelector(".search-select-model .select-selected").textContent = "Модель";

      var brandId = selectSelected.innerHTML;
      var xhr = new XMLHttpRequest();
      xhr.open("GET", location.origin + "/get_models.php?brand_id=" + brandId, true);
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          // Обновление списка моделей
          var modelSelect = document.getElementById("model-select");
          var modelList = document.querySelector(".search-select-model .select-items");
          modelList.innerHTML = "";
          var models = JSON.parse(xhr.responseText);
          modelSelect.innerHTML = "<option value='0'>Модель</option>";
          for (var i = 0; i < models.length; i++) {
            var option = document.createElement("option");
            option.value = models[i].id;
            option.text = models[i].name;
            modelSelect.appendChild(option);

            var c = document.createElement("div");
            c.textContent = models[i].name;
            c.addEventListener("click", function (e) {
              console.log(e);
              /* When an item is clicked, update the original select box,
                and the selected item: */
              var y, i, k, s, h, sl, yl;
              s = this.parentNode.parentNode.getElementsByTagName("select")[0];
              sl = s.length;
              h = this.parentNode.previousSibling;

              for (i = 0; i < sl; i++) {
                if (s.options[i].innerHTML == this.innerHTML) {
                  s.selectedIndex = i;
                  h.innerHTML = this.innerHTML;
                  y = this.parentNode.getElementsByClassName("same-as-selected");
                  yl = y.length;
                  for (k = 0; k < yl; k++) {
                    y[k].removeAttribute("class");
                  }
                  this.setAttribute("class", "same-as-selected");
                  break;
                }
              }
              h.click();
            });

            modelList.appendChild(c);
          }
        }
      };
      xhr.send();
    }
  });
});

// Настройка MutationObserver для отслеживания изменений внутри элемента select-selected
var config = { childList: true, subtree: true };
observer.observe(selectSelected, config);

document.querySelector(".search-submit__button").addEventListener("click", () => {
  const manufacturer = document.querySelector(".search-select-brand .select-selected");
  const model = document.querySelector(".search-select-model .select-selected");
  const yearStart = document.querySelector(".search-year-start .search-year__input");
  const yearEnd = document.querySelector(".search-year-end .search-year__input");
  const horseStart = document.querySelector(".search-horse-start .search-year__input");
  const horseEnd = document.querySelector(".search-horse-end  .search-year__input");
  let searchParams;
  if (model.textContent == "Модель") {
    if (manufacturer.textContent == "Марка") {
      searchParams = {
        yearStart: yearStart.value,
        yearEnd: yearEnd.value,
        horseStart: horseStart.value,
        horseEnd: horseEnd.value,
      };
    } else {
      searchParams = {
        manufacturer: manufacturer.textContent,
        yearStart: yearStart.value,
        yearEnd: yearEnd.value,
        horseStart: horseStart.value,
        horseEnd: horseEnd.value,
      };
    }
  } else {
    if (manufacturer.textContent == "Марка") {
      searchParams = {
        yearStart: yearStart.value,
        yearEnd: yearEnd.value,
        horseStart: horseStart.value,
        horseEnd: horseEnd.value,
        model: model.textContent,
      };
    } else {
      searchParams = {
        manufacturer: manufacturer.textContent,
        yearStart: yearStart.value,
        yearEnd: yearEnd.value,
        horseStart: horseStart.value,
        horseEnd: horseEnd.value,
        model: model.textContent,
      };
    }
  }
  const queryString = Object.keys(searchParams)
    .map((key) => key + "=" + searchParams[key])
    .join("&");
  window.location.href = location.origin + "/search?" + queryString;
});

document.querySelector(".header-navbar-search__input").addEventListener("keydown", function (event) {
  if (event.keyCode === 13) {
    event.preventDefault();
    document.querySelector(".form-search").submit();
  }
});
