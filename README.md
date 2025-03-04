Toko Online Laravel dan react native Integrasi Midtrans dan raja Ongkir
laravel : C:\laragon\www\tokoku
1. install Laravel via laragon
2. buka proyek di vscode
3. install blueprint
	composer require -W --dev laravel-shift/blueprint
	composer require --dev jasonmccreary/laravel-test-assertions //laravel test
	php artisan blueprint:new //membuat draft.yaml
	php artisan blueprint:build //membuat file laravel
	php artisan migrate:fresh
	php artisan db:seed
	php artisan blueprint:erase //menghapus file sebelumnya yang akan dibuat
4. buat skema di draft.yaml //model,migrasi,seeder dan factory
5. migrate database
6. install laravel filament //laravel panel dan user: a@a.com, pass:123
	1. composer require filament/filament:"^3.2" -W
 	2. php artisan filament:install --panels
	3. php artisan make:filament-user
	4. php artisan filament:optimize
	5. php artisan filament:optimize-clear
	6. jangan lupa aktifkan opcache di server laragon menu->php->ekstensi->opcache
	7. php artisan make:filament-resource Categories --generate
	8 php artisan make:filament-resource Product --generate
	9.php artisan make:filament-resource Order --generate
	10. php artisan make:filament-resource User --generate
	11. php artisan make:filament-relation-manager Order OrderItem order_id
	12. php artisan make:filament-relation-manager Order Payment order_id
	13. php artisan make:filament-relation-manager Order Shipping order_id
7. Buat API
	php artisan install:api  //akan membuat tabel personal_access_token, lalu php artisan migrate
	install midtrans dan rajaongkir dulu
	composer require midtrans/midtrans-php  ->buat services/rajaongkir
	composer require guzzlehttp/guzzle
	composer require rajaongkir/rajaongkir-php ->buat services/rajaongkir

	php artisan make:controller Api/AuthController --api
	php artisan make:controller Api/CategoryController --api
	php artisan make:controller Api/ProductController --api
	php artisan make:controller Api/OrderController --api
	php artisan make:controller Api/PaymentController --api
	php artisan make:controller Api/ShippingController --api

9.Testing api divscode menggunakan ekstensi Postman

10. Buat Proyek React Native dengan Expo
	1. npm install -g react-native-cli //install react cli supaya bisa buat proyek react
	2. npx create-expo-app@latest -t tabs/blank //buat proyek expo template bottom tabs atau blank default blank
	3. npm i expo axios @react-native-async-storage/async-storage @reduxjs/toolkit react-redux
11. npx expo install react-dom react-native-web @react-navigation/web @expo/metro-runtime //install ini untuk dijalanakan di web
12. Buat File dan struktur folder, dalam folder app/(tabs) berisi,home,whislist,history,profil, kemudian cart,checkout, onboarding,splash,login,register
13. Buat folder redux/store.js dan buat masing masing slicer
14. buat file di constant/restApi.js dengan kode :
	import axios from 'axios';
	import AsyncStorage from '@react-native-async-storage/async-storage';

	// Base URL JSON Server
	const BASE_URL = 'http://localhost:3001';

	// Fungsi GET
	export const getApi = async (endpoint, token = null) => {
  	try {
    	const headers = token ? { Authorization: `Bearer ${token}` } : {};
    	const response = await axios.get(`${BASE_URL}/${endpoint}`, { headers });
    	return response.data;
  	} catch (error) {
    	console.error(`Error GET ${endpoint}:`, error);
    	throw error;
  	}
	};

	// Fungsi POST
	export const postApi = async (endpoint, data, token = null) => {
  	try {
    	const headers = { 'Content-Type': 'application/json' };
    	if (token) headers.Authorization = `Bearer ${token}`;

    	const response = await axios.post(`${BASE_URL}/${endpoint}`, data, { headers });
    	return response.data;
  	} catch (error) {
    	console.error(`Error POST ${endpoint}:`, error);
    		throw error;
  	}
	};

	// Fungsi menyimpan token ke AsyncStorage
	export const saveToken = async (token) => {
  	await AsyncStorage.setItem('token', token);
	};

	// Fungsi mendapatkan token dari AsyncStorage
	export const getToken = async () => {
  	return await AsyncStorage.getItem('token');
	};

	// Fungsi menghapus token dari AsyncStorage
	export const removeToken = async () => {
  	await AsyncStorage.removeItem('token');
	};
15. buat file redux/store.js

