const member_list_obj = document.getElementById("member_list");
const sort_type_obj = document.getElementById("sort_type");

const member_sort_table = new SortableTable();
member_sort_table.setTable(member_list_obj);

member_sort_table.setCellRenderer((col, row) => {
    const col_value = row[col.id];

    if(typeof col_value != "undefined"){
        return '<td class="border px-4 py-2">' + col_value + '</td>';
    }
});

member_sort_table.setData(member);

member_sort_table.events()
     .on('sort', (event) => {
       console.log(`[SortableTable#onSort]
     event.colId=${event.colId}
     event.sortDir=${event.sortDir}
     event.data=\n${JSON.stringify(event.data)}`);
     });
