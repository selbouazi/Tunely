<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-buttons slot="start">
          <ion-back-button default-href="/tabs/tab1" text="Tornar" />
        </ion-buttons>
        <ion-title>{{ subcategoryName || 'Productes' }}</ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content>
      <ProductList :products="products" />
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonButtons, IonBackButton } from '@ionic/vue';
import ProductList from '@/components/ProductList.vue';

interface Product {
  id: number;
  name: string;
  description: string;
  price: string;
  stock: number;
  image: string;
  subcategory_id: number;
  category_id: number;
}

const route = useRoute();
const products = ref<Product[]>([]);
const subcategoryName = ref('');

onMounted(async () => {
  try {
    const res = await fetch(`http://127.0.0.1:8000/api/products/subcategory/${route.params.id}`);
    const data: Product[] = await res.json();
    data.sort((a, b) => a.name.localeCompare(b.name));
    products.value = data;

    if (data.length > 0) {
      const catRes = await fetch('http://127.0.0.1:8000/api/categories');
      const categories: any[] = await catRes.json();
      for (const cat of categories) {
        const sub = cat.subcategories.find((s: any) => s.id === Number(route.params.id));
        if (sub) {
          subcategoryName.value = sub.name;
          break;
        }
      }
    }
  } catch (e) {
    console.error('Error loading products by subcategory:', e);
  }
});
</script>
