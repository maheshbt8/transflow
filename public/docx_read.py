from sys import argv
import docx

context = []
def main():
    try:
        doc = docx.Document(argv[1])  # Creating word reader object.
        data = ""
        fullText = []
        for para in doc.paragraphs:
            fullText.append(para.text)
            data = '\n'.join(fullText)
            for sentence in data.split('\n'):
                if sentence == '':
                    continue
                # print('***********')
                context.append(sentence)
                # print(sentence)
            # print(context)
        print(context)
    except IOError:
        print('There was an error opening the file!')
        return
    return context

if __name__ == '__main__':
    main()
