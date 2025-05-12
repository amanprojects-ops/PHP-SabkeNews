function openWindow(url) {
    if (!url || typeof url !== "string") {
      console.error("Invalid URL provided.");
      return;
    }
  
    const width = 600;
    const height = 600;
    const left = (screen.width - width) / 2;
    const top = (screen.height - height) / 2;
  
    window.open(
      url,
      "_blank",
      `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=yes`
    );
  }
  