function w(t){const r=["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"][t.getMonth()],n=String(t.getDate()).padStart(2,"0");var o=`${r} ${n}`;return o}function f(t){const e=t.getFullYear(),r=String(t.getMonth()+1).padStart(2,"0"),n=String(t.getDate()).padStart(2,"0");return`${e}-${r}-${n}`}function v(t,e=0,r=0){console.log(e,r,"start and end date");const n=[];if(e==0&&r==0){const a=new Date;a.setDate(a.getDate()-a.getDay());for(let s=0;s<6;s++)e=new Date(a),e.setDate(a.getDate()-7*s),r=new Date(e),r.setDate(e.getDate()+6),n.push({startDate:e,endDate:r})}else{e=new Date(e),r=new Date(r),e.setDate(e.getDate()-7),console.log(e,"startDate");let a=r;a.setDate(a.getDate()+a.getDay());const s=a.getTime()-e.getTime(),u=Math.floor(s/(1e3*60*60*24*7));console.log(u,"weeks");for(let g=0;g<=u;g++){const d=new Date(a);d.setDate(a.getDate()-1);const c=new Date(d);c.setDate(d.getDate()-6),console.log(c>=e,e,c,"loop if check"),c>=e&&n.push({startDate:c,endDate:d}),a=c}}console.log(n,"chkinngng");var o=0,h=[];n.forEach(a=>{t.forEach(s=>{chkDate=s.date,f(a.startDate)<=chkDate&&f(a.endDate)>chkDate&&(o+=s.hours)}),h.push(o),o=0}),n.forEach(function(a){a.startDate=w(a.startDate),a.endDate=w(a.endDate)});const i=[];n.forEach(function(a){i.push(a.startDate+" - "+a.endDate)}),console.log(i),hoursWorked=h.reverse(),labels=i.reverse()}v(chartData);const b={labels,datasets:[{label:"Total Hours Worked",backgroundColor:"#1892C0",borderColor:"rgb(255, 99, 132)",data:hoursWorked}]},k={type:"bar",data:b,options:{scales:{y:{beginAtZero:!0}}}};var l=new Chart(document.getElementById("myChart"),k);function p(t){var e=t.rows({search:"applied"}).indexes(),r=[];e.each(function(o){var h=t.row(o).data();r.push(h)});var n=[];return r.forEach(o=>{const h=o[6].split(" "),i=new Date(h[0]);var a=o[10],s=/(\d+).*?(\d+)/,u=a.match(s);if(u)var g=u[1],d=u[2],c=g*1+d/60;else console.log("No match found.");n.push({date:f(i),hours:c})}),n}$(document).ready(function(){var t=$("#mechandiserDatatable").DataTable({scrollX:!0,paging:!0,searching:!0,ordering:!0,lengthChange:!1,pageLength:10,dom:"lBfrtip",buttons:["copy","excel","pdf","print"],pagingType:"full_numbers"});$("#store-search").on("change",function(){t.column(0).search(this.value).draw();var e=p(t);console.log(e),v(e),l.data.labels=labels,l.data.datasets[0].data=hoursWorked,l.update()}),$("#location-search").on("change",function(){t.column(1).search(this.value).draw();var e=p(t);console.log(e),v(e),l.data.labels=labels,l.data.datasets[0].data=hoursWorked,l.update()}),$("#merchandiser-search").on("change",function(){t.column(11).search(this.value).draw();var e=p(t);console.log(e),v(e),l.data.labels=labels,l.data.datasets[0].data=hoursWorked,l.update()}),$("#period-search").on("change",function(){if(this.value.includes("to")){let u=function(g,d){for(var c=new Date(g),m=new Date(d),D=[];c<=m;)D.push(f(new Date(c))),c.setDate(c.getDate()+1);return D};var a=u;const s=this.value.split("to");var e=s[0].trim();r=e.replace(/^\s+/,""),r=new Date(r);var r=f(r),n=s[1].trim();o=n.replace(/^\s+/,""),o=new Date(o);var o=f(o);t.column(8).search("",!0,!1).draw();var h=u(r,o);t.column(8).search(h.join("|"),!0,!1,!0).draw();var i=p(t);console.log(i),v(i,r,o),l.data.labels=labels,l.data.datasets[0].data=hoursWorked,l.update()}else console.log("The substring 'to' does not exist in the original string.")}),document.getElementById("clearDate").addEventListener("click",function(){t.column(8).search("",!0,!1).draw()})});