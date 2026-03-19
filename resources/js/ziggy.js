// @ts-ignore
export const Ziggy = {
    namedRoutes: {
        'conversations.index': {
            uri: 'api/conversations',
            methods: ['GET', 'HEAD'],
        },
        'conversations.show': {
            uri: 'api/conversations/{conversation}',
            methods: ['GET', 'HEAD'],
        },
        'conversations.toggle-ai': {
            uri: 'api/conversations/{conversation}/toggle-ai',
            methods: ['PATCH'],
        },
        'conversations.messages.index': {
            uri: 'api/conversations/{conversation}/messages',
            methods: ['GET', 'HEAD'],
        },
        'conversations.messages.store': {
            uri: 'api/conversations/{conversation}/messages',
            methods: ['POST'],
        },
        'orders.index': {
            uri: 'api/orders',
            methods: ['GET', 'HEAD'],
        },
        'orders.stats': {
            uri: 'api/orders/stats',
            methods: ['GET', 'HEAD'],
        },
        'orders.show': {
            uri: 'api/orders/{order}',
            methods: ['GET', 'HEAD'],
        },
        'orders.update': {
            uri: 'api/orders/{order}',
            methods: ['PATCH'],
        },
        'settings.index': {
            uri: 'api/settings',
            methods: ['GET', 'HEAD'],
        },
        'settings.update': {
            uri: 'api/settings',
            methods: ['POST'],
        },
        'settings.test-whatsapp': {
            uri: 'api/settings/test-whatsapp',
            methods: ['POST'],
        },
        'settings.test-instagram': {
            uri: 'api/settings/test-instagram',
            methods: ['POST'],
        },
        'inbox.index': {
            uri: 'inbox',
            methods: ['GET', 'HEAD'],
        },
        'inbox.show': {
            uri: 'inbox/{conversation}',
            methods: ['GET', 'HEAD'],
        },
        'orders.index': {
            uri: 'orders',
            methods: ['GET', 'HEAD'],
        },
        'settings.index': {
            uri: 'settings',
            methods: ['GET', 'HEAD'],
        },
    },
    defaultParameters: {
    },
};

Ziggy.defaultLocation = window.location.href;

// @ts-ignore
export class Router {
    constructor(name: string, params: Record<string, any>, absolute: boolean, location?: string) {
        this.name = name;
        this.params = params;
        this.absolute = absolute;
        this.location = location || Ziggy.defaultLocation;
    }

    get(name: string, params: Record<string, any> = {}, absolute = false) {
        const route = Ziggy.namedRoutes[name];
        if (!route) {
            throw new Error(`Route "${name}" not found`);
        }

        let uri = route.uri;

        // Replace parameters in URI
        Object.keys(params).forEach(key => {
            uri = uri.replace(`{${key}}`, params[key]);
        });

        // Remove optional parameters
        uri = uri.replace(/\/{[^}]+}/g, '');

        if (absolute) {
            const url = new URL(this.location);
            return `${url.protocol}//${url.host}${uri}`;
        }

        return uri;
    }
}

// @ts-ignore
export const route = (name: string, params: Record<string, any> = {}, absolute = false) => {
    const router = new Router(name, params, absolute);
    return router.get(name, params, absolute);
};
