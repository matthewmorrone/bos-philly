import { scrape } from '../scrape.js';
import puppeteer from 'puppeteer';

jest.mock('puppeteer');

test('handles invalid URLs gracefully', async () => {
  const mockGoto = jest.fn().mockRejectedValue(new Error('Invalid URL'));
  const mockPage = { goto: mockGoto };
  puppeteer.launch.mockResolvedValue({
    pages: jest.fn().mockResolvedValue([mockPage]),
    close: jest.fn(),
  });

  const errorSpy = jest.spyOn(console, 'error').mockImplementation(() => {});
  const result = await scrape('http://invalid');
  expect(result).toBeNull();
  expect(mockGoto).toHaveBeenCalled();
  expect(errorSpy).toHaveBeenCalled();
  errorSpy.mockRestore();
});
