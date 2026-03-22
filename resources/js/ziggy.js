const Ziggy = {"url":"http:\/\/localhost","port":null,"defaults":{},"routes":{"sanctum.csrf-cookie":{"uri":"sanctum\/csrf-cookie","methods":["GET","HEAD"]},"api.conversations.index":{"uri":"api\/conversations","methods":["GET","HEAD"]},"api.conversations.show":{"uri":"api\/conversations\/{conversation}","methods":["GET","HEAD"],"parameters":["conversation"],"bindings":{"conversation":"id"}},"api.conversations.toggle-ai":{"uri":"api\/conversations\/{conversation}\/toggle-ai","methods":["PATCH"],"parameters":["conversation"],"bindings":{"conversation":"id"}},"api.conversations.messages.index":{"uri":"api\/conversations\/{conversation}\/messages","methods":["GET","HEAD"],"parameters":["conversation"],"bindings":{"conversation":"id"}},"api.conversations.messages.store":{"uri":"api\/conversations\/{conversation}\/messages","methods":["POST"],"parameters":["conversation"],"bindings":{"conversation":"id"}},"api.orders.index":{"uri":"api\/orders","methods":["GET","HEAD"]},"api.orders.stats":{"uri":"api\/orders\/stats","methods":["GET","HEAD"]},"api.orders.show":{"uri":"api\/orders\/{order}","methods":["GET","HEAD"],"parameters":["order"],"bindings":{"order":"id"}},"api.orders.update":{"uri":"api\/orders\/{order}","methods":["PATCH"],"parameters":["order"],"bindings":{"order":"id"}},"api.settings.index":{"uri":"api\/settings","methods":["GET","HEAD"]},"api.settings.update":{"uri":"api\/settings","methods":["POST"]},"api.settings.test-whatsapp":{"uri":"api\/settings\/test-whatsapp","methods":["POST"]},"api.settings.test-instagram":{"uri":"api\/settings\/test-instagram","methods":["POST"]},"inbox.index":{"uri":"inbox","methods":["GET","HEAD"]},"inbox.show":{"uri":"inbox\/{conversation}","methods":["GET","HEAD"],"parameters":["conversation"]},"orders.index":{"uri":"orders","methods":["GET","HEAD"]},"settings.index":{"uri":"settings","methods":["GET","HEAD"]},"storage.local":{"uri":"storage\/{path}","methods":["GET","HEAD"],"wheres":{"path":".*"},"parameters":["path"]},"storage.local.upload":{"uri":"storage\/{path}","methods":["PUT"],"wheres":{"path":".*"},"parameters":["path"]}}};
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}

Ziggy.defaultLocation = typeof window !== 'undefined' ? window.location.href : '';

export class Router {
    constructor(name, params, absolute, location) {
        this.name = name;
        this.params = params;
        this.absolute = absolute;
        this.location = location || Ziggy.defaultLocation;
    }

    get(name, params = {}, absolute = false) {
        const routeObj = Ziggy.routes[name];
        if (!routeObj) {
            throw new Error(`Route "${name}" not found`);
        }

        let uri = routeObj.uri;

        // Replace parameters in URI
        if (params && typeof params === 'object') {
            Object.keys(params).forEach(key => {
                uri = uri.replace(`{${key}}`, params[key]);
            });
        } else if (params) {
            // If single param provided for the first binding
            uri = uri.replace(/\{[^}]+\}/, params);
        }

        // Remove optional parameters
        uri = uri.replace(/\/{[^}]+}/g, '');

        if (absolute) {
            const url = new URL(this.location);
            return `${url.protocol}//${url.host}/${uri}`;
        }

        return `/${uri}`;
    }
}

export const route = (name, params = {}, absolute = false) => {
    const router = new Router(name, params, absolute);
    return router.get(name, params, absolute);
};

export { Ziggy };
