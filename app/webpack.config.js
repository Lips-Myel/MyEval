const Encore = require('@symfony/webpack-encore');

// Configure manuellement l'environnement d'exécution si ce n'est pas déjà fait par la commande "encore".
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // Répertoire où les fichiers compilés seront stockés
    .setOutputPath('public/build/')
    // Chemin public utilisé par le serveur web pour accéder au répertoire de sortie
    .setPublicPath('/build')

    /*
     * CONFIGURATION DES ENTRÉES
     */
    .addEntry('app', './assets/app.js')

    // Optimisations Webpack
    .splitEntryChunks()
    .enableSingleRuntimeChunk()

    /*
     * CONFIGURATION DES FONCTIONNALITÉS
     */
    .cleanupOutputBeforeBuild() // Nettoie les fichiers de sortie avant de construire
    .enableBuildNotifications() // Active les notifications de build
    .enableSourceMaps(!Encore.isProduction()) // Active les sourcemaps en développement
    .enableVersioning(Encore.isProduction()) // Active la versioning des fichiers en production

    // Configuration de Babel
    .configureBabel((config) => {
        // Ajoute le preset React uniquement si nécessaire
        // Aucune duplication ici car `enableReactPreset()` est déjà activé
    })

    // Configuration de @babel/preset-env pour les polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.38';
    })

    // Activer le support React
    .enableReactPreset()

    // Ajouter manuellement un loader pour JSX et .js si nécessaire
    .addLoader({
        test: /\.(js|jsx)$/,
        exclude: /node_modules/,
        use: {
            loader: 'babel-loader',
            options: {
                presets: ['@babel/preset-react'], // Supprime cette ligne si `enableReactPreset()` suffit
            }
        }
    })

// Obtenir la configuration Webpack finale
const config = Encore.getWebpackConfig();

// Personnalisation de la configuration Webpack directement
config.resolve = {
    extensions: ['.js', '.jsx']
};

module.exports = config;
