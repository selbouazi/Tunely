<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title>Categories</ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Categories</ion-title>
        </ion-toolbar>
      </ion-header>

      <ion-list v-if="categories.length > 0">
        <ion-item-group v-for="category in categories" :key="category.id">
          <ion-item-divider>
            <ion-label>
              <h2>{{ category.name }}</h2>
            </ion-label>
          </ion-item-divider>

          <ion-item
            v-for="subcategory in category.subcategories"
            :key="subcategory.id"
            :router-link="`/products/subcategory/${subcategory.id}`"
            detail
          >
            <ion-icon :icon="chevronForwardOutline" slot="start" />
            <ion-label>{{ subcategory.name }}</ion-label>
          </ion-item>
        </ion-item-group>
      </ion-list>

      <div v-else class="ion-text-center ion-padding">
        <p>Carregant categories...</p>
      </div>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import {
  IonPage, IonHeader, IonToolbar, IonTitle, IonContent,
  IonList, IonItemGroup, IonItemDivider, IonLabel,
  IonItem, IonIcon,
} from '@ionic/vue';
import { chevronForwardOutline } from 'ionicons/icons';

interface Subcategory {
  id: number;
  name: string;
  slug: string;
}

interface Category {
  id: number;
  name: string;
  slug: string;
  subcategories: Subcategory[];
}

const categories = ref<Category[]>([]);

onMounted(async () => {
  try {
    const res = await fetch('http://127.0.0.1:8000/api/categories');
    categories.value = await res.json();
  } catch (e) {
    console.error('Error loading categories:', e);
  }
});
</script>
