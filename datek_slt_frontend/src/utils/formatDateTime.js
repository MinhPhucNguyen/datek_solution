import { computed } from "vue";

export const formatDateTime = computed(() => {
   return (createdAt) => {
      const datetime = new Date(createdAt);

      const formatDate = datetime.toLocaleDateString("en-US", {
         year: "numeric",
         month: "numeric",
         day: "numeric",
      });
      const created_at = new Date(formatDate);
      return `${created_at.getDate()}/${created_at.getMonth() + 1}/${created_at.getFullYear()}`;
   };
});
