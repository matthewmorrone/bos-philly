async function getPages(post_type, limit, rest) {
    try {
        const params = new URLSearchParams();
        params.set('action', 'list');
        params.set('post_type', post_type);
        if (limit !== undefined) params.set('limit', limit);
        if (rest !== undefined) params.set('rest', rest);
        const resp = await fetch('wp.php', { method: 'POST', body: params });
        return await resp.json();
    }
    catch (error) {
        console.error('list', post_type, error);
        return null;
    }
}

async function getPageByName(post_type, name) {
    try {
        const params = new URLSearchParams();
        params.set('action', 'get');
        params.set('post_type', post_type);
        params.set('name', name);
        const resp = await fetch('wp.php', { method: 'POST', body: params });
        return await resp.json();
    }
    catch (error) {
        console.error('get', post_type, name, error);
        return null;
    }
}
