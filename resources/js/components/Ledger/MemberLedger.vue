<template>
    <div>
        <hot-table :data="data" rowHeaders="true" colHeaders="true" licenseKey="non-commercial-and-evaluation"></hot-table>
    </div>
</template>

<script>

import { HotTable } from '@handsontable/vue';
import Handsontable from 'handsontable';

export default {
    data: function() {
      return {
        hotSettings: {
          data: Handsontable.helper.createSpreadsheetData(6, 10),
          colHeaders: true
        }
      }
    },
    components: {
      HotTable
    }   
}
</script>

<style src="../../../../node_modules/handsontable/dist/handsontable.full.css"></style>
