const member_list_obj=document.getElementById("member_list"),sort_type_obj=document.getElementById("sort_type"),member_sort_table=new SortableTable;member_sort_table.setTable(member_list_obj),member_sort_table.setCellRenderer((col,row)=>{const col_value=row[col.id];if(void 0!==col_value)return'<td class="border px-4 py-2">'+col_value+"</td>"}),member_sort_table.setData(member),member_sort_table.events().on("sort",event=>{console.log(`[SortableTable#onSort]\n     event.colId=${event.colId}\n     event.sortDir=${event.sortDir}\n     event.data=\n${JSON.stringify(event.data)}`)});