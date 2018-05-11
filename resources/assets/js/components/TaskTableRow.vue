<template>
   <tr>
      <td>
         <div class="input-group">
            <input type="checkbox" @click="completeTask(index, task)"/>
         </div>
      </td>
      <td><span v-bind:class="{strikethrough: task.status === 'completed'}">{{task.title}}</span></td>
      <td><button class="btn btn-primary btn-sm">Status</button>
         <button @click="deleteTask(index, task.id, 'completed')" class="btn btn-default btn-sm">Trash</button>
      </td>
   </tr>
</template>