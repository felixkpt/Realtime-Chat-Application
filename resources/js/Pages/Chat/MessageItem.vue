<template>
    <div class="border rounded p-1 my-1">
        <h5 class="text-xs text-gray-500">{{ message.user.name }}</h5>
        <div>{{ message.message }}</div>
    </div>
</template>
<script>
export default {
    props: {
        message: {
            Object
        }
    }
}
</script>