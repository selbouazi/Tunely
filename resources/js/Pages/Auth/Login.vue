<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Iniciar sesión" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600 bg-green-50 border border-green-200 rounded px-4 py-3">
            {{ status }}
        </div>

        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-[#1a1a1a]">Iniciar sesión</h1>
            <p class="text-[#1a1a1a]/60 text-sm mt-1">Accede a tu cuenta de Tunely</p>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div>
                <InputLabel for="email" value="Correo electrónico" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full bg-[#FEFDDF]"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="tu@email.com"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <div class="flex justify-between items-center">
                    <InputLabel for="password" value="Contraseña" />
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-xs text-[#E87F24] hover:text-[#FFC81E] hover:underline"
                    >
                        ¿Olvidaste tu contraseña?
                    </Link>
                </div>
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full bg-[#FEFDDF]"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex items-center">
                <Checkbox name="remember" v-model:checked="form.remember" />
                <label class="ms-2 text-sm text-[#1a1a1a]/70 select-none">Recordarme</label>
            </div>

            <PrimaryButton
                class="w-full justify-center py-3 text-base"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                {{ form.processing ? 'Entrando...' : 'Iniciar sesión' }}
            </PrimaryButton>
        </form>

        <div class="mt-6 pt-5 border-t border-[#73A5CA]/30 text-center">
            <p class="text-sm text-[#1a1a1a]/60">
                ¿No tienes cuenta?
                <Link :href="route('register')" class="text-[#E87F24] font-semibold hover:text-[#FFC81E] hover:underline">
                    Regístrate aquí
                </Link>
            </p>
        </div>
    </GuestLayout>
</template>
