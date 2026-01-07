<?php

namespace Database\Seeders;

use App\Models\Translate;
use Illuminate\Database\Seeder;

class KeyboardShortcutTranslationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $translations = [
            // English translations
            ['locale' => 'en', 'key' => 'KeyboardShortcuts', 'value' => 'Keyboard Shortcuts'],
            ['locale' => 'en', 'key' => 'Actions', 'value' => 'Actions'],
            ['locale' => 'en', 'key' => 'Quantity', 'value' => 'Quantity'],
            ['locale' => 'en', 'key' => 'Navigation', 'value' => 'Navigation'],
            ['locale' => 'en', 'key' => 'CustomerSearch', 'value' => 'Customer Search'],
            ['locale' => 'en', 'key' => 'QuickItem', 'value' => 'Quick Item'],
            ['locale' => 'en', 'key' => 'ReprintLast', 'value' => 'Reprint Last Sale'],
            ['locale' => 'en', 'key' => 'IncrementQty', 'value' => 'Increase Qty'],
            ['locale' => 'en', 'key' => 'DecrementQty', 'value' => 'Decrease Qty'],
            ['locale' => 'en', 'key' => 'SetExactQty', 'value' => 'Set Exact Qty'],
            ['locale' => 'en', 'key' => 'RemoveItem', 'value' => 'Remove Item'],
            ['locale' => 'en', 'key' => 'NavigateResults', 'value' => 'Navigate Results'],
            ['locale' => 'en', 'key' => 'SelectProduct', 'value' => 'Select Product'],
            ['locale' => 'en', 'key' => 'ClearSearch', 'value' => 'Clear/Cancel'],
            
            // French translations
            ['locale' => 'fr', 'key' => 'KeyboardShortcuts', 'value' => 'Raccourcis Clavier'],
            ['locale' => 'fr', 'key' => 'Actions', 'value' => 'Actions'],
            ['locale' => 'fr', 'key' => 'Quantity', 'value' => 'Quantité'],
            ['locale' => 'fr', 'key' => 'Navigation', 'value' => 'Navigation'],
            ['locale' => 'fr', 'key' => 'CustomerSearch', 'value' => 'Recherche Client'],
            ['locale' => 'fr', 'key' => 'QuickItem', 'value' => 'Article Rapide'],
            ['locale' => 'fr', 'key' => 'ReprintLast', 'value' => 'Réimprimer Dernière Vente'],
            ['locale' => 'fr', 'key' => 'IncrementQty', 'value' => 'Augmenter Qté'],
            ['locale' => 'fr', 'key' => 'DecrementQty', 'value' => 'Diminuer Qté'],
            ['locale' => 'fr', 'key' => 'SetExactQty', 'value' => 'Définir Qté Exacte'],
            ['locale' => 'fr', 'key' => 'RemoveItem', 'value' => 'Supprimer Article'],
            ['locale' => 'fr', 'key' => 'NavigateResults', 'value' => 'Naviguer Résultats'],
            ['locale' => 'fr', 'key' => 'SelectProduct', 'value' => 'Sélectionner Produit'],
            ['locale' => 'fr', 'key' => 'ClearSearch', 'value' => 'Effacer/Annuler'],
            
            // Arabic translations
            ['locale' => 'ar', 'key' => 'KeyboardShortcuts', 'value' => 'اختصارات لوحة المفاتيح'],
            ['locale' => 'ar', 'key' => 'Actions', 'value' => 'الإجراءات'],
            ['locale' => 'ar', 'key' => 'Quantity', 'value' => 'الكمية'],
            ['locale' => 'ar', 'key' => 'Navigation', 'value' => 'التنقل'],
            ['locale' => 'ar', 'key' => 'CustomerSearch', 'value' => 'بحث العميل'],
            ['locale' => 'ar', 'key' => 'QuickItem', 'value' => 'منتج سريع'],
            ['locale' => 'ar', 'key' => 'ReprintLast', 'value' => 'إعادة طباعة آخر عملية بيع'],
            ['locale' => 'ar', 'key' => 'IncrementQty', 'value' => 'زيادة الكمية'],
            ['locale' => 'ar', 'key' => 'DecrementQty', 'value' => 'تقليل الكمية'],
            ['locale' => 'ar', 'key' => 'SetExactQty', 'value' => 'تحديد الكمية بالضبط'],
            ['locale' => 'ar', 'key' => 'RemoveItem', 'value' => 'حذف المنتج'],
            ['locale' => 'ar', 'key' => 'NavigateResults', 'value' => 'التنقل في النتائج'],
            ['locale' => 'ar', 'key' => 'SelectProduct', 'value' => 'اختيار المنتج'],
            ['locale' => 'ar', 'key' => 'ClearSearch', 'value' => 'مسح/إلغاء'],
            
            // Spanish translations
            ['locale' => 'es', 'key' => 'KeyboardShortcuts', 'value' => 'Atajos de Teclado'],
            ['locale' => 'es', 'key' => 'Actions', 'value' => 'Acciones'],
            ['locale' => 'es', 'key' => 'Quantity', 'value' => 'Cantidad'],
            ['locale' => 'es', 'key' => 'Navigation', 'value' => 'Navegación'],
            ['locale' => 'es', 'key' => 'CustomerSearch', 'value' => 'Buscar Cliente'],
            ['locale' => 'es', 'key' => 'QuickItem', 'value' => 'Artículo Rápido'],
            ['locale' => 'es', 'key' => 'ReprintLast', 'value' => 'Reimprimir Última Venta'],
            ['locale' => 'es', 'key' => 'IncrementQty', 'value' => 'Aumentar Cant.'],
            ['locale' => 'es', 'key' => 'DecrementQty', 'value' => 'Disminuir Cant.'],
            ['locale' => 'es', 'key' => 'SetExactQty', 'value' => 'Establecer Cant. Exacta'],
            ['locale' => 'es', 'key' => 'RemoveItem', 'value' => 'Eliminar Artículo'],
            ['locale' => 'es', 'key' => 'NavigateResults', 'value' => 'Navegar Resultados'],
            ['locale' => 'es', 'key' => 'SelectProduct', 'value' => 'Seleccionar Producto'],
            ['locale' => 'es', 'key' => 'ClearSearch', 'value' => 'Limpiar/Cancelar'],
        ];

        foreach ($translations as $translation) {
            Translate::updateOrCreate(
                ['locale' => $translation['locale'], 'key' => $translation['key']],
                ['value' => $translation['value']]
            );
        }

        $this->command->info('Keyboard shortcut translations seeded successfully!');
    }
}
