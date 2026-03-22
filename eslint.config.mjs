import pluginVue from 'eslint-plugin-vue';
import tsParser from '@typescript-eslint/parser';

export default [
  ...pluginVue.configs['flat/essential'],
  {
    ignores: ["public/**", "vendor/**", "bootstrap/**", "storage/**", "*.blade.php"],
    rules: {
      'vue/multi-word-component-names': 'off',
      'vue/return-in-computed-property': 'off',
      'vue/no-mutating-props': 'off'
    },
    languageOptions: {
      parserOptions: {
        parser: tsParser
      }
    }
  }
];
